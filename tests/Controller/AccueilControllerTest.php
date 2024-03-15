<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccueilControllerTest extends WebTestCase
{

    /**
     * Tester l'accessibilité́ de la page d'accueil
     * @return void
     */
    public function testAccessPage(){
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}