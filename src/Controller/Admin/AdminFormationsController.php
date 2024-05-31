<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController
{

    /**
     * @var FormationRepository
     */
    private $FormationRepository;

    private $PlaylistRepository; //si
    /**
     * @param FormationRepository $FormationRepository
     * @param CategorieRepository $CategorieRepository
     */
    public function  __construct( FormationRepository $FormationRepository, CategorieRepository $CategorieRepository, PlaylistRepository $playlistRepository)
    {
        $this->FormationRepository = $FormationRepository;
        $this->CategorieRepository = $CategorieRepository;
        $this->PlaylistRepository = $playlistRepository; // si
    }

    /**
     * @Route("/admin/formations", name="admin.formations")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->CategorieRepository->findAll();
        $formtaions = $this->FormationRepository->findAll();

        return $this->render('Admin/admin_formations.html.twig', [
            'formations' => $formtaions,
            'categories' => $categories
        ]);
    }

    /**
     *  Si on me demande de permettre de supprimer une formation dans les pages de modification et d'ajout d'une playlist
     * @Route("/admin/formations/supprimer/{id}/{redirection}", name="admin.formation.supprimer")
     * @param Formation $formation
     * @return Response
     */
    /**
    public function supprimerFormation(Formation $formation, string $redirection, RequestStack $requestStack) : Response
    {
        $this->FormationRepository->remove($formation, true);

        if($redirection == "admin_formations") {
            return $this->redirectToRoute('admin.formations');
        }else{
            // Recharge la même page
            $request = $requestStack->getCurrentRequest();
            $referer = $request->headers->get('referer');

            // Redirige vers la page précédente
            return $this->redirect($referer);
        }
    }
     */



    /**
     * @Route("/admin/formations/supprimer/{id}/{redirection}", name="admin.formation.supprimer")
     * @param Formation $formation
     * @return Response
     */
    public function supprimerFormation(Formation $formation, string $redirection, RequestStack $requestStack) : Response
    {
        $this->FormationRepository->remove($formation, true);

        return $this->redirectToRoute('admin.formations');
    }



    /**
     * @Route("/admin/formation/modifier/{id}", name="admin.formation.modifier")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function modifierFormation(Formation $formation, Request $request ) : Response
    {
        $title = "Modifier une formation";
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->FormationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("Admin/admin_formation_modifier.html.twig", [
            'title' => $title,
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }

    /**
     * @Route("/admin/formations/sort/{champ}/{ordre}/{table}", name="admin.formation.sort")
     * @param $champ
     * @param $ordre
     * @param $table
     * @return Response
     */
    public function sort($champ, $ordre, $table="") : Response
    {
        $formations = $this->FormationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->CategorieRepository->findAll();

        return $this->render('Admin/admin_formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/formations/rechercher/{champ}/{table}", name="admin.formation.rechercher")
     * @param Request $request
     * @param $champ
     * @param $table
     * @return Response
     */
    public function findAllContaining(Request $request, $champ, $table="") : Response
    {
        $valeur = $request->request->get('rechercher');
        $formations = $this->FormationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->CategorieRepository->findAll();

        return $this->render('Admin/admin_formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
        ]);
    }

    /**
     * @Route("/admin/formations/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request ) : Response
    {
        $title = "Ajouter une formation";
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);

        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->FormationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("Admin/admin_formation_modifier.html.twig", [
            'title' => $title,
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }

    /**
     * Si on me demande de permettre d'ajouter une formation depuis les pages de modification et d'ajout d'une playlist
     * Ajout d'une formation depuis les pages de modification et d'ajout d'une playlist
     * @Route("/admin/formations/ajout/{id}", name="admin.formation.ajoutdepuisplaylist")
     * @param Request $request
     * @return Response
     */
    /**
    public function ajouter(string $id, Request $request) : Response
    {
        $title = "Ajouter une formation";
        $formation = new Formation();
        $playlist = $this->PlaylistRepository->find($id);
        $formation->setPlaylist($playlist);

        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->remove('playlist');

        $formFormation->handleRequest($request);

        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->FormationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("Admin/admin_formation_modifier.html.twig", [
            'title' => $title,
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }
     */

}
