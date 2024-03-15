<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    /**
     * Test de la fonction qui retourne la date de création en format chaîne de caractères.
     * @return void
     */
    public function testGetPublishedAtString()
    {
        $date = new \DateTime('2024/03/13');

        $formation  = new Formation();
        $formation->setPublishedAt($date);
        $this->assertEquals('13/03/2024', $formation->getPublishedAtString());
    }
}