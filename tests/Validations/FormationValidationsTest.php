<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase
{

    /**
     * Création d'une formation
     * @param $titre
     * @return Formation
     */
    public function getFormation($titre)
    {
        return(new Formation())
            ->setTitle($titre);
    }

    /**
     * Compare le nombre d'erreurs retournées avec le nombre d'erreurs attendues
     * @param $formation
     * @param $nombreErreursAttendues
     * @return void
     */
    public function assertErrors($formation, $nombreErreursAttendues)
    {
        self::bootKernel();
        $validator =  self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nombreErreursAttendues, $error);
    }

    /**
     * Test d'une formation avec la date de publication anterieure à la date actuelle
     * @return void
     */
    public function testValidePublishedAt()
    {
        // Création d'une formation anterieur a la date actuelle
        $formation = $this->getFormation('titre')->setPublishedAt(new \DateTime('now'));

        // Ne doit pas retourner d'erreur
        $this->assertErrors($formation, 0);
    }

    /**
     * Test d'une formation avec la date de publication psérieure à la date actuelle
     * @return void
     */
    public function testNonValidePublishedAt()
    {
        // Création d'une formation poserieur a la date actuelle
        $formation = $this->getFormation('titre')->setPublishedAt(new \DateTime('2030-12-16'));

        //  Doit retourner une erreur
        $this->assertErrors($formation, 1);
    }

}