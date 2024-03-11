<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController
{
    private $FormationRepository;

    public function  __construct( FormationRepository $FormationRepository, CategorieRepository $CategorieRepository)
    {
        $this->FormationRepository = $FormationRepository;
        $this->CategorieRepository = $CategorieRepository;
    }

    /**
     * @Route("/admin/formations", name="admin.formations")
     */
    public function index(): Response
    {
        $categories = $this->CategorieRepository->findAll();
        $formtaions = $this->FormationRepository->findAll();

        return $this->render('admin/admin_formations.html.twig', [
            'formations' => $formtaions,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/formations/supprimer/{id}", name="admin.formation.supprimer")
     * @param Formation $formation
     * @return Response
     */
    public function supprimerFormation(Formation $formation) : Response
    {
        $this->FormationRepository->remove($formation, true);

        return $this->redirectToRoute('admin.formations');
    }

    /**
     * @Route("/admin/formation/modifier/{id}", name="admin.formation.modifier")
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

}
