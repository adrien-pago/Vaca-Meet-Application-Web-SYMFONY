<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = 'password123';

        $client->submit($form);

        $this->assertResponseRedirects('/');

        $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Welcome');
    }
}

