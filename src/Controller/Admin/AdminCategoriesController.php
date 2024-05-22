<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{

    /**
     * @var CategorieRepository
     */
    private $CategorieRepository;

    /**
     * @param CategorieRepository $CategorieRepository
     */
    public function __construct(CategorieRepository $CategorieRepository)
    {
        $this->CategorieRepository = $CategorieRepository;
    }

    /**
     * @Route ("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index() : Response
    {
        $categories = $this->CategorieRepository->findAll();
        return $this->render('Admin/admin_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route ("/admin/categories/{id}/supprimer", name="admin.categories.supprimer")
     * @param Categorie $categorie
     * @return Response
     */
    public function supprimerCategorie(Categorie $categorie ) : Response
    {
        $formation = $categorie->getFormations();
        if($formation->isEmpty()){
            $this->CategorieRepository->remove($categorie, true);
        }else{
            $this->addFlash('error_suppression_categorie', 'Suppression impossible : La categorie contien des formations');
        }

        return $this->redirectToRoute('admin.categories');
    }

    /**
     * @Route ("/admin/categorie/ajouter", name="admin.categorie.ajouter")
     * @param Request $request
     * @return Response
     */
    public function ajouterCategorie(Request $request ) : Response
    {
        $valeur = $request->get('valeur');
        if ($this->CategorieRepository->findOneBy(['name' => $valeur]) != null) {
            $this->addFlash('error_suppression_categorie', 'Ajout impossible : La categorie existe déja');
        }else{
            $categorie = new Categorie();
            $categorie->setName($valeur);
            $this->CategorieRepository->add($categorie, true);
        }

        return $this->redirectToRoute('admin.categories');
    }

    // Test si le déploiement continu fonctionne toujour.
}