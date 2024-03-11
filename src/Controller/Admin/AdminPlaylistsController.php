<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPlaylistsController extends AbstractController
{

    /**
     * @var PlaylistRepository
     */
    private $PlaylistRepository;

    /**
     * @param PlaylistRepository $PlaylistRepository
     */
    public function __construct(PlaylistRepository $PlaylistRepository, CategorieRepository $CategorieRepository)
    {
        $this->PlaylistRepository = $PlaylistRepository;
        $this->CategorieRepository = $CategorieRepository;
    }


    /**
     * @Route ("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index() : Response
    {
        $categories = $this->CategorieRepository->findAll();
        $playlists = $this->PlaylistRepository->findAll();
        return $this->render('Admin/admin_playlists.html.twig',
        [
            'categories' => $categories,
            'playlists' => $playlists
        ]);
    }

    /**
     * @Route ("/admin/playlists/{id}/supp", name="admin.playlists.supp")
     * @return Response
     */
    public function supprimerPlaylist(Playlist $playlist ) : Response
    {
        try {
            $this->PlaylistRepository->remove($playlist, true);
        }catch (Exception $e) {
            $this->addFlash('playlist_request', 'Impossible de supprimer la playlist car elle contient des formations');
        }

        return $this->redirectToRoute('admin.playlists');
    }


    /**
     * @Route ("/admin/playlists/{id}/modifier", name="admin.playlists.modifier")
     * @return Response
     */
    public function modifierPlaylist(Playlist $playlist, Request $request ) : Response
    {
        $title = 'Modifier une playlist';
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);

        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->PlaylistRepository->add($playlist, true);
            return $this->redirectToRoute("admin.playlists");
        }

        return $this->render("Admin/admin_playlist_modifier.html.twig", [
            'formPlaylist' => $formPlaylist->createView(),
            'title' => $title,
            'formations' => $playlist->getFormations()
        ]);
    }


    /**
     * @Route ("/admin/playlists/ajouter", name="admin.playlists.ajouter")
     * @return Response
     */
    public function ajout(Request $request): Response {
        $title = 'Ajouter une playlist';
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->PlaylistRepository->add($playlist, true);
            return $this->redirectToRoute("admin.playlists");
        }

        return $this->render("Admin/admin_playlist_modifier.html.twig", [
            'formPlaylist' => $formPlaylist->createView(),
            'formations' => $playlist->getFormations(),
            'title' => $title
        ]);
    }


    /**
     * @Route ("/admin/playlists/rechercher/{champ}/{table}", name="admin.playlists.rechercher")
     * @return void
     */
    public function findAllContaining(Request $request, $champ, $table = "") : Response
    {
        $valeur = $request->get('valeur');
        $categories = $this->CategorieRepository->findAll();
        $playlists = $this->PlaylistRepository->findByContainValue($champ, $valeur, $table);

        return $this->render('Admin/admin_playlists.html.twig', [
            'categories' => $categories,
            'playlists' => $playlists
        ]);
    }

    /**
     * @Route ("/admin/playlists/sort/{champ}/{ordre}", name="admin.playlists.sort")
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch ($champ){
            case"name":
                $playlists = $this->PlaylistRepository->findAllOrderByName($ordre);
                break;
            case "numberFormations":
                $playlists = $this->PlaylistRepository->findAllOrderByNumberFormations($ordre);
                break;
            default;
        }

        $categories = $this->CategorieRepository->findAll();
        return $this->render("Admin/admin_playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
}