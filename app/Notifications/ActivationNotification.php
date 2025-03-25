<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivationNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Activation de votre compte')
            ->line('Bienvenue ! Merci de vous être inscrit.')
            ->line('Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous.')
            ->action('Activer mon compte', url('/api/account/activate?token=' . $this->token))
            ->line('Si vous n\'avez pas créé de compte, aucune action n\'est requise.')
            ->line('Ce lien d\'activation expirera dans 24 heures.');
    }
}