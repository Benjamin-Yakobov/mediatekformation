<?php

namespace App\Tests\Controller;

use \Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase
{

    /**
     * Filtrer les playlists par le nom
     * @return void
     */
    public function testFiltrerPlaylist(){
        $client = static::createClient();
        $client->request('GET','playlists/recherche/name');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours Triggers'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours Triggers');
    }

    /**
     * Filtrer les playlists par la catégorie
     * @return void
     */
    public function testFiltrerCategorie(){
        $client = static::createClient();
        $client->request('GET','playlists/recherche/id/categories');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => ''
        ]);
        $this->assertCount(27, $crawler->filter('h5'));
    }

    /**
     * Trier les playlists selon le nombre de formations
     * @return void
     */
    public function testSortFormation(){
        $client = static::createClient();

        $client->request('GET', "/playlists/tri/numberFormation/ASC");
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');

        $client->request('GET', "/playlists/tri/numberFormation/DESC");
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');

    }

    /**
     * Tester le premier lien lien des playlists
     * @return void
     */
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/playlists/playlist/13', $uri);
        $this->assertSelectorTextContains('h4','Bases de la programmation (C#)');
    }
}