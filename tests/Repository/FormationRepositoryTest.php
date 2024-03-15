<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    /**
     * Récuperation d'une instance FormationRepository
     * @return FormationRepository|object|null
     */
    public function recupRepository()
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /**
     * Tester le nombre de formations
     * @return void
     */
    public function testNombreDeFormations()
    {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }

    /**
     * Création d'une nouvelle formation
     * @param $title
     * @return Formation
     */
    public function newFormation($title): Formation{
        $formation = (new Formation())
            ->setTitle($title);

        return $formation;
    }

    /**
     * Tester l'ajout d'une nouvelle formation
     * @return void
     */
    public function testAdd()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation('Formation test');
        $nombreFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nombreFormations + 1, $repository->count([]));
    }

    /**
     * Tester la suppression d'une formation
     * @return void
     */
    public function testRemove()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation('Formation test');
        $repository->add($formation, true);
        $nombreFormations = $repository->count([]);

        $repository->remove($formation, true);
        $this->assertEquals($nombreFormations - 1, $repository->count([]));
    }

    /**
     * Tester le filtre
     * @return void
     */
    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation('Formation test');
        $repository->add($formation, true);

        $formations = $repository->findByContainValue("title", "Formation test");
        $nombreFormations = count($formations);
        $this->assertEquals(1, $nombreFormations);
        $repository->remove($formation, true);
    }

}