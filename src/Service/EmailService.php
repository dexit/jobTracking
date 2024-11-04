<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{

    public function __construct(private MailerInterface $mailer, private LoggerInterface $logger)
    {
    }

    public function sendHtmlEmail(String $to, String $subject, String $content)
    {
        $email = (new Email())
            ->from('no-reply@job-tracking.free.nf')  // Adresse d'expéditeur personnalisée
            ->to($to)                             // Destinataire
            ->subject($subject)                   // Sujet
            ->html($content);                     // Contenu de l'e-mail

        try {
            $this->mailer->send($email);
            $this->logger->info('Email envoyé avec succès à ' . $to);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
            // Gérer l'exception si nécessaire
            // Gérer l'exception si nécessaire
        }
    }
}
