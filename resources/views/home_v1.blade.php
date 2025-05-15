<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nota Premiada Cacequi</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon-cacequi.png') }}">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
</head>

<body class="bg-white text-gray-800 font-sans">

    {{-- Header --}}
    <header class="bg-gray-900 shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/brasao_cacequi.png') }}" alt="Prefeitura de Cacequi" class="w-14 h-14">
                <span class="text-white text-xl font-bold">Nota Premiada Cacequi</span>
            </div>
            <a href="{{ route('filament.admin.auth.login') }}"
                class="bg-white text-gray-800 px-4 py-2 rounded hover:bg-gray-200 font-semibold">
                Acessar minha conta
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-gray-100 py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Ganhe prêmios cadastrando suas notas fiscais</h1>
            <p class="text-lg text-gray-700 mb-6">
                A cada R$200 em compras no comércio local, você ganha um número da sorte.<br>
                O sorteio final ocorre em <strong>28 de dezembro</strong>!
            </p>
            <div class="flex justify-center gap-4 flex-wrap">
                <a href="https://play.google.com/store/apps/details?id=com.exemplo.notapremiada" target="_blank"
                    class="bg-amber-500 text-white px-6 py-3 rounded-lg text-lg hover:bg-amber-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M325.3 234.3L104.3 32.1c-6.8-6.1-17.3-1.2-17.3 8.3v431.2c0 9.5 10.6 14.4 17.3 8.3l221-202.2c5.5-5.1 5.5-13.5 0-18.4zM372.4 288.3l-41.6 37.7 58.5 53.5c6.8 6.2 18.1 1.1 18.1-8.2v-123c0-9.3-11.3-14.4-18.1-8.2l-58.5 53.5 41.6 37.7z" />
                    </svg>
                    Baixar o aplicativo
                </a>
            </div>
        </div>
    </section>

    {{-- Carrossel de Prêmios --}}
    <section class="py-16 bg-white">
        <h2 class="text-3xl text-center font-bold text-gray-800 mb-8">Prêmios da edição atual</h2>
        <div class="swiper max-w-5xl mx-auto px-6">
            <div class="swiper-wrapper">
                <div class="swiper-slide flex flex-col items-center text-center">
                    <img src="{{ asset('images/premios/premio1.jpg') }}" alt="Automóvel Fiat Mobi"
                        class="rounded-xl shadow w-72 mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">1º Prêmio</h3>
                    <p class="text-gray-600">Um automóvel da marca Fiat/Mobi</p>
                </div>
                <div class="swiper-slide flex flex-col items-center text-center">
                    <img src="{{ asset('images/premios/premio2.jpg') }}" alt="Notebook"
                        class="rounded-xl shadow w-72 mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">2º Prêmio</h3>
                    <p class="text-gray-600">Um Notebook</p>
                </div>
                <div class="swiper-slide flex flex-col items-center text-center">
                    <img src="{{ asset('images/premios/premio4.jpg') }}" alt="Televisor 32 polegadas"
                        class="rounded-xl shadow w-72 mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">3º Prêmio</h3>
                    <p class="text-gray-600">Um Televisor 32 polegadas</p>
                </div>
                <div class="swiper-slide flex flex-col items-center text-center">
                    <img src="{{ asset('images/premios/premio4.jpg') }}" alt="Televisor 42 polegadas"
                        class="rounded-xl shadow w-72 mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">4º Prêmio</h3>
                    <p class="text-gray-600">Um Televisor 42 polegadas</p>
                </div>
            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </section>


    {{-- Números da última campanha --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-10">Resultados da edição anterior</h2>
            <div class="grid md:grid-cols-4 gap-8 text-xl font-semibold text-gray-700">
                <div>
                    <span class="text-4xl text-amber-600 block">7.820</span>
                    Cupons cadastrados
                </div>
                <div>
                    <span class="text-4xl text-amber-600 block">R$ 894.000</span>
                    Total em notas fiscais
                </div>
                <div>
                    <span class="text-4xl text-amber-600 block">42</span>
                    Empresas participantes
                </div>
                <div>
                    <span class="text-4xl text-amber-600 block">1.538</span>
                    Downloads do app
                </div>
            </div>
        </div>
    </section>

    {{-- Como participar --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Como participar</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <h3 class="text-xl font-semibold text-amber-600 mb-2">1. Compre em Cacequi</h3>
                    <p>Inclua seu CPF nas notas fiscais de estabelecimentos locais.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-amber-600 mb-2">2. Use o app oficial</h3>
                    <p>Cadastre as notas emitidas no ano corrente pelo aplicativo.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-amber-600 mb-2">3. Concorra no sorteio</h3>
                    <p>A cada R$200 acumulados, você recebe um número da sorte.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Depoimentos --}}
    <section class="py-16 bg-gray-100 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-10">Depoimentos</h2>
            <div class="grid md:grid-cols-2 gap-8 text-gray-700">
                <div class="bg-white p-6 rounded-xl shadow">
                    <p class="italic">“Participei pela primeira vez e adorei. Agora não deixo de cadastrar minhas
                        notas!”</p>
                    <span class="mt-2 block font-semibold text-amber-600">Carla Menezes</span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow">
                    <p class="italic">“Uma iniciativa excelente da prefeitura. Simples, digital e com ótimos prêmios!”
                    </p>
                    <span class="mt-2 block font-semibold text-amber-600">Eduardo Pereira</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Rodapé --}}
    <footer class="bg-gray-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm">
            &copy; {{ date('Y') }} Prefeitura de Cacequi – Todos os direitos reservados.<br>
            Dúvidas? Entre em contato pelo WhatsApp (55) 99999-9999.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Opcional: define quantos slides por vez, ajustando ao tamanho da tela
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                640: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            },
        });
    </script>

</body>

</html>
