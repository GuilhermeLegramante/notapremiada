<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cupom Rejeitado</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; color: #111827; padding: 20px; line-height: 1.6;">

    <div
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">

        <h2 style="font-size: 24px; font-weight: 600; color: #1f2937; margin-bottom: 20px;">Seu cupom não foi validado
        </h2>

        <p>Olá <strong>{{ $cupom->user->name }}</strong>,</p>

        <p>Seu cupom enviado no dia <strong>{{ $cupom->created_at->format('d/m/Y \à\s H:i') }}</strong> foi analisado
            pela equipe da campanha e <span style="color: #dc2626; font-weight: bold;">não foi validado</span>.</p>

        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Fornecedor:</strong> {{ $cupom->fornecedor }}</p>
            <p style="margin: 0;"><strong>Valor:</strong> R$ {{ number_format($cupom->valor_total, 2, ',', '.') }}</p>
        </div>

        <p style="margin-bottom: 20px;"><strong>Motivo:</strong> Após análise, a equipe considerou que o cupom não atende
            aos critérios de validação.</p>

        <p
            style="margin-bottom: 20px; background-color: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #facc15;">
            <strong>Observação:</strong> Caso o cupom tenha sido rejeitado apenas por conter erro no valor total, ele
            será recadastrado manualmente por nossa equipe e <strong>novos números de sorteio serão gerados</strong>,
            enquanto os números antigos serão removidos do sistema.
        </p>

        <p>Se tiver dúvidas, entre em contato com o suporte da campanha.</p>

        <p style="margin-top: 40px;">Atenciosamente,<br><strong>Nota Premiada - Cacequi/RS</strong>
        </p>
    </div>

    <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px;">
        Esta é uma mensagem automática. Por favor, não responda este e-mail.
    </p>
</body>

</html>
