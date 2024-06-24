<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSubmitRegistrationForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Créer')->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[nomCamping]' => 'Camping Test',
            'registration_form[siret]' => '12345678901234',
            'registration_form[plainPassword][first]' => 'password123', // Mise à jour ici
            'registration_form[plainPassword][second]' => 'password123', // Mise à jour ici
            'registration_form[rgpdAccepted]' => true,
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }
}
