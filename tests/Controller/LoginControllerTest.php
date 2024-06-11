<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['login[email]'] = 'adrien.pago@gmail.com';
        $form['login[password]'] = 'test';

        $client->submit($form);

        $this->assertResponseRedirects('/home');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Bienvenue');
    }
}
