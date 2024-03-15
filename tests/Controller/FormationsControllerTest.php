<?php

namespace App\Tests\Controller;

use \Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationsControllerTest extends WebTestCase
{
    /**
     * Tester le tri des formations sur les titres
     * @return void
     */
    public function testSortFormation(){
        $client = static::createClient();

        $client->request('GET', "/formations/tri/title/ASC");
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');

        $client->request('GET', "/formations/tri/title/DESC");
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');

    }

    /**
     * Tester le tri des formations sur les playlists
     * @return void
     */
    public function testSortPlaylist()
    {
        $client = static::createClient();

        $client->request('GET', "/formations/tri/name/ASC/playlist");
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');

        $client->request('GET', "/formations/tri/name/DESC/playlist");
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');

    }

    /**
     * Tester le tri des formations sur les dates
     * @return void
     */
    public function testSortDate()
    {
        $client = static::createClient();

        $client->request('GET', "/formations/tri/publishedAt/ASC");
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');

        $client->request('GET', "/formations/tri/publishedAt/DESC");
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }

    /**
     * Tester le filtre des formations sur les titres
     * @return void
     */
    public function testFilterFormation()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/title');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'MCD exercice 7 : associations porteuses'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'MCD exercice 7 : associations porteuses');
    }

    /**
     * Tester le filtre des formations sur les playlists
     * @return void
     */
    public function testFiltrerPlaylist(){
        $client = static::createClient();
        $client->request('GET','/formations/recherche/name/playlist');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours Merise/2'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours Merise/2');
    }

    /**
     * Tester le filtre des formations sur les categories
     * @return void
     */
    public function testFilterCategorie()
    {
        $client = static::createClient();
        $client->request('GET','/formations/recherche/id/categories');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => ''
        ]);

        $this->assertCount(237, $crawler->filter("H5"));
    }

    /**
     * Tester le premier lien des formations
     * @return void
     */
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink("Miniatures des Vidéos de Formations");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations/formation/1', $uri);
        $this->assertSelectorTextContains('h4','Eclipse n°8 : Déploiement');
    }


}