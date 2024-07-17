<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Attribute\AsCommand;

// Ajoutez cette ligne au début de votre commande
#[AsCommand(name: 'app:send-test-email')]
class SendTestEmailCommand extends Command
{
    protected static $defaultName = 'app:send-test-email';

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Envoie un email de test.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->text('This is a test email.')
            ->html('<p>This is a test email.</p>');

        $this->mailer->send($email);

        $output->writeln('Test email sent.');

        return Command::SUCCESS;
    }
}
