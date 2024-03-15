<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{

    /**
     * Recupere une instance de PlaylistRepository
     * @return PlaylistRepository|object|null
     */
    public function recupRepository()
    {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Création d'une nouvelle playlist
     * @param $name
     * @return Playlist
     */
    public function newPlaylist($name)
    {
        $playlist = new Playlist();
        $playlist->setName($name);
        return $playlist;
    }

    /**
     * Tester l'ajout d'une nouvelle playlist
     * @return void
     */
    public function testAdd()
    {
        $repository = $this->recupRepository();
        $nombrePlaylists = $repository->count([]);
        $playlist = $this->newPlaylist('Playlist test');

        $repository->add($playlist, true);
        $this->assertEquals($nombrePlaylists + 1, $repository->count([]));
    }

    /**
     * Tester la suppression d'une playlist
     * @return void
     */
    public function testRemove()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist('Playlist test');
        $repository->add($playlist, true);
        $nombrePlaylists = $repository->count([]);

        $repository->remove($playlist, true);
        $this->assertEquals($nombrePlaylists - 1, $repository->count([]));
    }

    /**
     * Tester le filtre
     * @return void
     */
    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist('Playlist test');
        $repository->add($playlist, true);

        $playlists = $repository->findByContainValue("name", "Playlist test");
        $nombrePlaylists = count($playlists);
        $this->assertEquals(1, $nombrePlaylists);
        $repository->remove($playlist, true);
    }

}