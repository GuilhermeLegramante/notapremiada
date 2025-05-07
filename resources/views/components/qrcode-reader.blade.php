<div class="mt-4">
    <div id="reader" class="mx-auto max-w-sm"></div>

    <div class="flex justify-center mt-4">
        <x-filament::button color="primary" type="button" onclick="startScanner()">
            Ler QR Code
        </x-filament::button>
    </div>
</div>
<br>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let scannerInstance;

    // Define the function globally so it can be used in the onclick handler
    function startScanner() {
        if (scannerInstance) {
            return;
        }

        scannerInstance = new Html5Qrcode("reader");

        scannerInstance.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            (decodedText, decodedResult) => {
                if (scannerInstance) {
                    scannerInstance.stop().then(() => {
                        // Obtém a referência do Livewire
                        const livewireComponent = @this;

                        if (livewireComponent) {
                            // Chama o método diretamente do Livewire
                            livewireComponent.set('chave_acesso', decodedText);

                            // Exemplo de chamada de outro método se necessário
                            livewireComponent.call('getNfData');
                        }

                        scannerInstance = null;
                    }).catch((err) => {
                        console.error("Erro ao parar o scanner:", err);
                    });
                }
            },
            (errorMessage) => {
                // Ignore erros de leitura
            }
        ).catch(err => {
            console.error("Erro ao iniciar o scanner:", err);
        });
    }

    // Wait for Livewire to be loaded before emitting the event
    document.addEventListener('livewire:load', function() {
        console.log('Livewire carregado!');
    });
</script>
