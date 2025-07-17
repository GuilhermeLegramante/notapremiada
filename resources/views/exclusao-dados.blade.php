<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitação de Exclusão de Dados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: sans-serif;
            padding: 2rem;
            max-width: 700px;
            margin: auto;
            color: #333;
        }

        h1 {
            color: #00549f;
        }

        form {
            margin-top: 2rem;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input, textarea, button {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            box-sizing: border-box;
        }

        button {
            background-color: #00549f;
            color: white;
            border: none;
            margin-top: 2rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #003f7f;
        }
    </style>
</head>
<body>
    <h1>Solicitação de Exclusão de Dados</h1>
    <p>Preencha o formulário abaixo para solicitar a exclusão dos seus dados pessoais do sistema Nota Premiada de Cacequi. A solicitação será analisada pela equipe responsável e poderá ser respondida em até 15 dias úteis.</p>

    @if(session('success'))
        <div style="color: green; margin-top: 1rem;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('exclusao.enviar') }}" method="POST">
        @csrf
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" required pattern="\d{11}" title="Informe 11 dígitos numéricos">

        <label for="email">E-mail de contato</label>
        <input type="email" id="email" name="email" required>

        <label for="mensagem">Motivo ou observações (opcional)</label>
        <textarea id="mensagem" name="mensagem" rows="4"></textarea>

        <button type="submit">Solicitar Exclusão</button>
    </form>
</body>
</html>
