<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class StructureControllerTest extends WebTestCase
{
    private function loginUser()
    {
        $client = static::createClient();

        // Récupérer l'utilisateur de test
        $user = $this->getContainer()->get('doctrine')->getRepository(UserInterface::class)->findOneByEmail('adrien.pago@gmail.com');

        // Connecter l'utilisateur
        $client->loginUser($user);

        return $client;
    }

    public function testIndex()
    {
        $client = $this->loginUser();
        $crawler = $client->request('GET', '/structures');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Structures');
    }

    public function testEditStructure()
    {
        $client = $this->loginUser();
        
        // Simuler la requête POST pour modifier une structure
        $client->request('POST', '/structure/1/edit', [
            'libelleStructure' => 'Updated Name',
            'nbStructure' => '5',
        ], [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']); // Simuler une requête AJAX

        // Vérifier que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifier que le contenu de la réponse est en JSON
        $response = $client->getResponse();
        $this->assertJson($response->getContent());

        // Décoder le JSON et vérifier les données spécifiques
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $content);
        $this->assertSame('Structure mise à jour avec succès!', $content['message']);
    }

    public function testDeleteStructure()
    {
        $client = $this->loginUser();
        
        // Simuler la requête DELETE pour supprimer une structure
        $client->request('DELETE', '/structure/1/delete', [], [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']); // Simuler une requête AJAX

        // Vérifier que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifier que le contenu de la réponse est en JSON
        $response = $client->getResponse();
        $this->assertJson($response->getContent());

        // Décoder le JSON et vérifier les données spécifiques
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $content);
        $this->assertSame('Structure supprimée avec succès!', $content['message']);
    }
}
