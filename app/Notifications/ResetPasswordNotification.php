<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
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
            ->subject('Réinitialisation de votre mot de passe')
            ->line('Vous recevez cet e-mail parce que nous avons reçu une demande de réinitialisation de votre mot de passe.')
            ->action('Réinitialiser le mot de passe', url('/api/password-reset/confirm?token=' . $this->token))
            ->line('Si vous n\'avez pas demandé de réinitialisation, aucune action supplémentaire n\'est requise.');
    }
}