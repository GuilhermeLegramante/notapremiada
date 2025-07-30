<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Em manutenção • {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #1f2937;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
            padding: 1rem;
        }

        .logo {
            width: 80px;
            margin-bottom: 1.5rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 1.1rem;
            color: #4b5563;
        }

        .container {
            max-width: 500px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('images/logo_nota_premiada.png') }}" alt="Logo" class="logo"> {{-- Substitua pelo seu logo --}}
        <h1>🔧 Em manutenção</h1>
        <p>Estamos atualizando o sistema <strong>{{ config('app.name') }}</strong>.<br>
            Por favor, volte em alguns minutos.</p>
    </div>
</body>

</html>
