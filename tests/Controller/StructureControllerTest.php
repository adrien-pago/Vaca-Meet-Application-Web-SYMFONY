<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StructureControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/structures');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Structures');
    }

    public function testEditStructure()
    {
        $client = static::createClient();
        
        // Simuler la requête POST pour modifier une structure
        $client->request('POST', '/structure/1/edit', [
            'libelleStructure' => 'Updated Name',
            'nbStructure' => '5',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Structure mise à jour avec succès!']);
    }

    public function testDeleteStructure()
    {
        $client = static::createClient();
        
        // Simuler la requête DELETE pour supprimer une structure
        $client->request('DELETE', '/structure/1/delete');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Structure supprimée avec succès!']);
    }
}
