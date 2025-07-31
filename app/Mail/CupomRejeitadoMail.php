<?php

namespace App\Mail;

use App\Models\Cupom;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CupomRejeitadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Cupom $cupom;

    public function __construct(Cupom $cupom)
    {
        $this->cupom = $cupom;
    }

    public function build()
    {
        return $this->subject('Cupom rejeitado após análise')
            ->view('mail.cupom-rejeitado')
            ->with([
                'cupom' => $this->cupom,
            ]);
    }
}
