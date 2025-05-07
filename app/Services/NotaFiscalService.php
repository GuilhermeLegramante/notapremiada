<?php

namespace App\Services;

use DateTime;
use DOMDocument;
use DOMXPath;

class NotaFiscalService
{
    /**
     *  Ex. 43250493209765017940652090001057721242905216|2|1|1| → Chave de acesso (44 dígitos)
     *   2 → Versão do QR Code (versão 2 do padrão da NFC-e)
     *   1 → Ambiente de homologação/produção
     *     1 = Produção
     *     2 = Homologação (teste)
     *   1 → Tipo de emissão
     *     1 = Normal
     *     2 = Contingência FS
     *     3 = SCAN
     *     4 = DPEC
     *     etc.
     */
    public static function getDataFromQrCode($qrCode)
    {
        // $qrCode = '43250492754738014708654000000363061276394257|2|1|1|';

        $url = "https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p={$qrCode}|2|1|1|";

        $html = file_get_contents($url);

        $dadosNota = [];

        // Carregar o HTML na DOMDocument
        $dom = new DOMDocument();
        @$dom->loadHTML($html);  // Suprimir warnings de HTML mal formado

        // Criar um objeto XPath para navegar pelo DOM
        $xpath = new DOMXPath($dom);

        // Dados da empresa
        $empresaNome = $xpath->query("//div[@class='txtTopo']");
        if ($empresaNome->length > 0) {
            $empresaNome = $empresaNome->item(0)->textContent;
        } else {
            $empresaNome = 'Nome não encontrado';
        }
        $dadosNota['empresa_nome'] = trim($empresaNome);

        $empresaCNPJ = $xpath->query("//div[@class='text'][contains(text(), 'CNPJ:')]");
        if ($empresaCNPJ->length > 0) {
            $empresaCNPJ = $empresaCNPJ->item(0)->textContent;
            $empresaCNPJ = preg_replace('/[^0-9\/-]/', '', $empresaCNPJ);  // Filtra apenas números, '/' e '-'
        } else {
            $empresaCNPJ = 'CNPJ não encontrado';
        }
        $dadosNota['empresa_cnpj'] = trim($empresaCNPJ);

        // Endereço da empresa
        $empresaEndereco = $xpath->query("//div[@class='text'][not(contains(text(), 'CNPJ:'))]");
        if ($empresaEndereco->length > 0) {
            $empresaEndereco = $empresaEndereco->item(0)->textContent;
        } else {
            $empresaEndereco = 'Endereço não encontrado';
        }
        $dadosNota['empresa_endereco'] = trim($empresaEndereco);

        // Extrair os itens da nota
        $itens = [];
        $itemRows = $xpath->query("//tr[starts-with(@id, 'Item')]");
        foreach ($itemRows as $itemRow) {
            $item = [];
            $item['nome'] = trim($xpath->query(".//span[@class='txtTit']", $itemRow)->item(0)->textContent);
            $item['codigo'] = trim($xpath->query(".//span[@class='RCod']", $itemRow)->item(0)->textContent);
            $item['quantidade'] = trim($xpath->query(".//span[@class='Rqtd']", $itemRow)->item(0)->textContent);
            $item['valor_unitario'] = trim($xpath->query(".//span[@class='RvlUnit']", $itemRow)->item(0)->textContent);
            $item['valor_total'] = trim($xpath->query(".//span[@class='valor']", $itemRow)->item(0)->textContent);
            $itens[] = $item;
        }
        $dadosNota['itens'] = $itens;

        // Extrair o total da nota
        $totalNota = $xpath->query("//div[@id='totalNota']//div[@id='linhaTotal']//span[@class='totalNumb txtMax']");
        if ($totalNota->length > 0) {
            $totalNota = $totalNota->item(0)->textContent;
        } else {
            $totalNota = 0;
        }
        $dadosNota['total'] = (float) str_replace(',', '.', trim($totalNota));

        // Extrair a quantidade total de itens
        $qtdItens = $xpath->query("//div[@id='totalNota']//div[@id='linhaTotal']//span[@class='totalNumb']");
        if ($qtdItens->length > 0) {
            $qtdItens = $qtdItens->item(0)->textContent;
        } else {
            $qtdItens = 'Quantidade não encontrada';
        }
        $dadosNota['qtd_itens'] = trim($qtdItens);

        // Extrair a forma de pagamento
        $formaPagamento = $xpath->query("//div[@id='linhaForma']//span[@class='totalNumb txtTitR']");
        if ($formaPagamento->length > 0) {
            $formaPagamento = $formaPagamento->item(0)->textContent;
        } else {
            $formaPagamento = 'Forma de pagamento não encontrada';
        }
        $dadosNota['forma_pagamento'] = trim($formaPagamento);

        // Extrair o valor pago
        $valorPago = $xpath->query("//div[@id='linhaTotal']//span[@class='totalNumb']");
        if ($valorPago->length > 0) {
            $valorPago = $valorPago->item(1)->textContent; // O segundo span que contém o valor pago
        } else {
            $valorPago = 0;
        }
        $dadosNota['valor_pago'] = (float) str_replace(',', '.', trim($valorPago));

        // Extrair os tributos totais incidentes
        $tributos = $xpath->query("//div[@id='linhaTotal']//span[@class='totalNumb txtObs']");
        if ($tributos->length > 0) {
            $tributos = $tributos->item(0)->textContent;
        } else {
            $tributos = 'Tributos não encontrados';
        }
        $dadosNota['tributos'] = trim($tributos);

        // Informações da nota (Número, Série, Protocolo, etc.)
        $informacoesNota = $xpath->query("//div[@data-role='collapsible']//li");
        $informacoes = [];
        foreach ($informacoesNota as $info) {
            $informacoes[] = trim($info->textContent);
        }
        $dadosNota['informacoes_nota'] = $informacoes;

        // Data de Emissão
        preg_match('/Emissão:\s*<\/strong>(\d{2}\/\d{2}\/\d{4}) (\d{2}:\d{2}:\d{2})/', $html, $matches);

        $data = $matches[1]; // 21/04/2025
        $hora = $matches[2]; // 14:47:55

        $dadosNota['data_emissao'] = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');

        // Chave de acesso
        $chaveAcesso = $xpath->query("//span[@class='chave']");
        if ($chaveAcesso->length > 0) {
            $chaveAcesso = $chaveAcesso->item(0)->textContent;
        } else {
            $chaveAcesso = 'Chave de acesso não encontrada';
        }
        $dadosNota['chave_acesso'] = trim($chaveAcesso);

        // Retornar os dados extraídos
        return $dadosNota;
    }
}
