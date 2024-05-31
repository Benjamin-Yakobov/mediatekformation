<?php
namespace App\Controller;

use App\Entity\NoteCommentaire;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\NoteCommentaireRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    public const ROUTE_FORMATIONS = "pages/formations.html.twig";

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    private $noteCommentaireRepository;

    private $utilisateursRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository, NoteCommentaireRepository $noteCommentaireRepository, UtilisateursRepository $utilisateursRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
        $this->noteCommentaireRepository = $noteCommentaireRepository;
    }
    
    /**
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render($this::ROUTE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this::ROUTE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this::ROUTE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render('pages/formation.html.twig', [
            'formation' => $formation,
        ]);
    }

    /**
     * Controller pour afficher les formations comprises entre deux dates.
     * @Route("/formations/dateInterval}", name="findFormationsBetweenDates")
     * @param Request $request
     * @return Response
     */
    public function findFormationsBetweenDates(Request $request)
    {
        $date_debut = $request->request->get('date_debut');
        $date_fin = $request->request->get('date_fin');

        $dateTimeDebut = new \DateTime($date_debut);
        $dateTimeFin = new \DateTime($date_fin);

        $formations = $this->formationRepository->findFormationsBetweenDates($dateTimeDebut, $dateTimeFin);
        $categories = $this->categorieRepository->findAll();

        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    /**
     * Route("/commenter/formation", name="formation.commenter"")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function commenterVideo($id, Request $request )
    {
        return $this->redirectToRoute("formations");

    }


}
