<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase
{

    /**
     * Récuperation d'une instance de CategorieRepository
     * @return CategorieRepository|object|null
     */
    public function recupRepository()
    {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Création d'une instance de Categorie
     * @param $name
     * @return Categorie
     */
    public function newCategorie($name)
    {
        return (new Categorie())
            ->setName($name);
    }

    /**
     * Tester l'ajout d'une Categorie
     * @return void
     */
    public function testAdd()
    {
        $repository = $this->recupRepository();
        $nombreCategorie = $repository->count([]);
        $categorie = $this->newCategorie('Categorie test');
        $repository->add($categorie, true);
        $this->assertEquals($nombreCategorie + 1, $repository->count([]));
    }

    /**
     * Tester la suppression d'une Categorie
     * @return void
     */
    public function testRemove()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie('Categorie test');
        $repository->add($categorie, true);
        $nombreCategorie = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nombreCategorie - 1, $repository->count([]));
    }

}