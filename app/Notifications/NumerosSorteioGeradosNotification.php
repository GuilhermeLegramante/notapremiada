<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Models\Cupom;

class NumerosSorteioGeradosNotification extends Notification
{
    use Queueable;

    public Cupom $cupom;
    public int $quantidade;
    public array $numeros;

    public function __construct(Cupom $cupom, int $quantidade, array $numeros)
    {
        $this->cupom = $cupom;
        $this->quantidade = $quantidade;
        $this->numeros = $numeros;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $usuario = $this->cupom->user;

        $message = (new MailMessage)
            ->subject('Novos Números de Sorteio Gerados')
            ->greeting("Olá, administrador!")
            ->line("Um novo cupom foi cadastrado e gerou números de sorteio.")
            ->line("**Usuário:** {$usuario->name} (ID: {$usuario->id})")
            ->line("**Valor da Nota:** R$ " . number_format($this->cupom->valor_total, 2, ',', '.'))
            ->line("**Nº do Cupom:** #{$this->cupom->id}")
            ->line("**Números Gerados ({$this->quantidade}):**")
            ->salutation("Obrigado por utilizar nosso sistema.");

        foreach ($this->numeros as $n) {
            $message->line("• {$n}");
        }

        $message->action('Ver Cupom', "https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p={$this->cupom->chave_acesso}|2|1|1|");

        return $message;
    }
}
