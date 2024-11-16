<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MesaPartesVirtual extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    public function __construct($datos=[])
    {
        $this->datos=$datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->datos["correo"], $this->datos["remitente"])->view('plantilla_mail.contact_web', $this->datos);
    }
}
