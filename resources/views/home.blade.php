<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nota Premiada Cacequi</title>
    <link rel="icon" href="/images/favicon-cacequi.png" type="image/png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png" />
    <meta name="theme-color" content="#1E3A8A" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A',
                        primaryLight: '#3B82F6', // azul mais claro
                        primaryDark: '#1E40AF' // azul mais escuro
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-white text-gray-800 font-sans">

    <!-- Barra Superior -->
    <header class="bg-gradient-to-r from-primaryDark via-primary to-primaryLight shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="images/brasao_cacequi.png" alt="Prefeitura de Cacequi" class="w-12 h-12 sm:w-14 sm:h-14">
                <span class="text-white text-lg sm:text-xl font-bold text-center sm:text-left">
                    Nota Premiada Cacequi
                </span>
            </div>
            <a href="{{ route('filament.admin.auth.login') }}"
                class="bg-white text-primary px-4 py-2 rounded-full font-semibold hover:bg-blue-100 transition text-sm sm:text-base">
                Minha conta
            </a>
        </div>
    </header>


    <!-- Hero -->
    <section class="relative bg-cover bg-center text-white py-20 text-center px-6"
        style="background-image: url('/images/bg-cacequi.jpg');">
        <!-- Overlay com gradiente -->
        <div class="absolute inset-0 bg-gradient-to-r from-primaryDark via-primary to-primaryLight opacity-80"></div>

        <!-- Conteúdo -->
        <div class="relative z-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Nota Premiada Cacequi</h1>
            <h1 class="text-3xl font-bold mb-4">
                Ganhe prêmios cadastrando suas notas fiscais!
            </h1>
            <p class="text-lg md:text-xl mb-6">
                A cada R$200 em compras no comércio local, você ganha um número da sorte.<br>
                O sorteio final ocorre em <strong>28 de dezembro</strong>!
            </p>

            <div id="countdown" class="text-2xl md:text-3xl font-bold mb-6"></div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                <a href="/apk/Nota Premiada.apk" target="_blank"
                    class="bg-white text-primary px-6 py-3 rounded-full font-bold hover:bg-blue-100 transition">
                    Baixar no Android
                </a>
                <a href="https://apps.apple.com/app/idSEU_APP_ID" target="_blank"
                    class="bg-white text-primary px-6 py-3 rounded-full font-bold hover:bg-blue-100 transition">
                    Baixar no iOS
                </a>
            </div>

            <p class="mt-4 text-sm text-blue-200 italic">
                Cadastre-se pelo aplicativo <strong>ou</strong> pelo site.
            </p>
        </div>
    </section>


    <!-- Prêmios -->
    <section class="py-16 px-6 max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Prêmios da Campanha</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div class="bg-white shadow-lg rounded-2xl p-6 text-center border">
                <img src="images/premios/kwid.jpg" alt="Carro" class="mx-auto mb-4 h-40 object-contain" />
                <h3 class="text-xl font-semibold mb-2">Renault Kwid Zen 0km</h3>
                <p>O grande prêmio da campanha! Um carro novinho para transformar sua vida.</p>
            </div>
            <div class="bg-white shadow-lg rounded-2xl p-6 text-center border">
                <img src="images/premios/moto.jpg" alt="Motocicleta" class="mx-auto mb-4 h-40 object-contain" />
                <h3 class="text-xl font-semibold mb-2">Motocicleta 0km</h3>
                <p>Mais liberdade e mobilidade com uma moto novinha em folha!</p>
            </div>
            <div class="bg-white shadow-lg rounded-2xl p-6 text-center border">
                <img src="images/premios/tv.jpg" alt="Smart TV" class="mx-auto mb-4 h-40 object-contain" />
                <h3 class="text-xl font-semibold mb-2">Smart TV 50''</h3>
                <p>Assista seus conteúdos favoritos com mais qualidade e estilo.</p>
            </div>
            <div class="bg-white shadow-lg rounded-2xl p-6 text-center border">
                <img src="images/premios/smartphone.jpg" alt="Smartphone" class="mx-auto mb-4 h-40 object-contain" />
                <h3 class="text-xl font-semibold mb-2">Smartphone Xiaomi Redmi Note 13</h3>
                <p>Um Smartphone moderno para o seus estudos e dia-a-dia!</p>
            </div>
        </div>
    </section>


    <!-- Resultados -->
    <section class="bg-blue-50 py-16 px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Resultados da Última Edição</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-6xl mx-auto text-center">
            <div>
                <p class="text-4xl font-bold text-primary">250.450</p>
                <p class="text-gray-700 mt-2">Cupons cadastrados</p>
            </div>
            <div>
                <p class="text-4xl font-bold text-primary">236</p>
                <p class="text-gray-700 mt-2">Empresas participantes</p>
            </div>
            <div>
                <p class="text-4xl font-bold text-primary">R$ 11.250.000</p>
                <p class="text-gray-700 mt-2">Em notas cadastradas</p>
            </div>
            <div>
                <p class="text-4xl font-bold text-primary">3.200</p>
                <p class="text-gray-700 mt-2">Downloads do app</p>
            </div>
        </div>
    </section>

    <!-- Vídeo -->
    <section class="bg-gray-100 py-16 px-6 text-center">
        <h2 class="text-3xl font-bold mb-6">Assista e Entenda a Campanha</h2>
        <div class="max-w-4xl mx-auto aspect-video">
            <iframe class="w-full h-full rounded-xl" src="https://www.youtube.com/embed/SEU_ID_DO_VIDEO"
                title="Vídeo Nota Premiada" frameborder="0" allowfullscreen></iframe>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="py-16 px-6 max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Depoimentos de Participantes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow rounded-xl p-6">
                <p class="italic">"Ganhei um Notebook e fiquei muito feliz! Super recomendo."</p>
                <p class="mt-4 font-bold text-primary">— Maria da Silva</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <p class="italic">"Foi fácil participar e ainda ganhei uma televisão. Incrível!"</p>
                <p class="mt-4 font-bold text-primary">— João Pereira</p>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gradient-to-r from-primaryDark via-primary to-primaryLight text-white py-20 text-center px-6">
        <h2 class="text-3xl font-bold mb-4">Como Participar</h2>
        <p class="mb-6 text-lg">Baixe o aplicativo ou acesse o site e cadastre suas notas fiscais para concorrer!</p>
        <div class="flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="/apk/Nota Premiada.apk" target="_blank"
                class="bg-white text-primary px-6 py-3 rounded-full font-bold hover:bg-blue-100 transition">
                Baixar para Android
            </a>
            <a href="https://apps.apple.com/app/idSEU_APP_ID" target="_blank"
                class="bg-white text-primary px-6 py-3 rounded-full font-bold hover:bg-blue-100 transition">
                Baixar para iOS
            </a>
        </div>
        <p class="mt-4 text-sm text-blue-200 italic">Disponível gratuitamente nas lojas de aplicativos ou pelo site.</p>
    </section>



    <!-- Rodapé -->
    <footer class="bg-blue-900 text-white text-center py-6 px-4">
        <p>&copy;
            <script>
                document.write(new Date().getFullYear())
            </script> Prefeitura de Cacequi - Todos os direitos reservados.
        </p>
        <p class="text-sm mt-1">Sistema desenvolvido por HardSoft Sistemas</p>
    </footer>

    <!-- WhatsApp -->
    <a href="https://wa.me/5555999181805?text=Ol%C3%A1!%20Gostaria%20de%20saber%20mais%20sobre%20a%20Nota%20Premiada%20Cacequi."
        target="_blank"
        class="fixed bottom-4 right-4 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 z-50 transition-all"
        aria-label="Fale conosco no WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M20.52 3.48A11.8 11.8 0 0 0 3.48 20.52l-1.2 4.32 4.44-1.17A11.8 11.8 0 0 0 20.52 3.48ZM12 21a8.94 8.94 0 0 1-4.46-1.19L6.1 20.9l.74-2.7A8.94 8.94 0 1 1 21 12c0 4.95-4.05 9-9 9Zm3.87-6.27-1.2-.63a6.12 6.12 0 0 1-2.52-2.52l-.63-1.2a.8.8 0 0 0-1.14-.32l-1.5.75a.8.8 0 0 0-.3 1.14 9.25 9.25 0 0 0 4.2 4.2.8.8 0 0 0 1.14-.3l.75-1.5a.8.8 0 0 0-.3-1.14Z" />
        </svg>
        WhatsApp
    </a>

    <!-- Aviso de Cookies -->
    <div id="cookie-consent"
        class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 z-50">
        <p class="text-sm max-w-xl">
            Usamos cookies para melhorar sua experiência no site. Ao continuar navegando, você concorda com nossa
            <a href="#" class="underline text-blue-400 hover:text-blue-600" target="_blank">Política de
                Privacidade</a>.
        </p>
        <button id="accept-cookies" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            Aceitar
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consentKey = 'cookieConsentGiven';
            const consentBanner = document.getElementById('cookie-consent');
            const acceptBtn = document.getElementById('accept-cookies');

            // Verifica se já deu consentimento antes
            if (localStorage.getItem(consentKey) === 'true') {
                consentBanner.style.display = 'none';
            }

            acceptBtn.addEventListener('click', function() {
                localStorage.setItem(consentKey, 'true');
                consentBanner.style.display = 'none';
            });
        });

        function atualizarContador() {
            const fim = new Date('2025-12-28T00:00:00'); // Data do sorteio
            const agora = new Date();
            const diff = fim - agora;

            if (diff <= 0) {
                document.getElementById('countdown').innerHTML = 'O sorteio é hoje!';
                return;
            }

            const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutos = Math.floor((diff / (1000 * 60)) % 60);
            const segundos = Math.floor((diff / 1000) % 60);

            document.getElementById('countdown').innerHTML =
                `Faltam ${dias}d ${horas}h ${minutos}m ${segundos}s para o sorteio!`;
        }

        setInterval(atualizarContador, 1000);
        atualizarContador();
    </script>

</body>

</html>
