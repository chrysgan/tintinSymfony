<?php

namespace App\Controller;

use App\Repository\ObjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    #[Route('/galerie', name: 'app_gallery')]
    public function index(Request $request, ObjetRepository $objetRepository): Response
    {
        // 1. On récupère le numéro de page dans l'URL.
        // Exemple : /galerie?page=2
        // Si rien n'est indiqué, on utilise la page 1.
        $page = $request->query->getInt('page', 1);

        // 2. Sécurité simple : on empêche une page inférieure à 1.
        $page = max(1, $page);

        // 3. Nombre d'objets affichés par page.
        $limit = 24;

        // 4. Calcul du point de départ pour la requête SQL.
        // Page 1 : offset 0 Page 2 : offset 24 Page 3 : offset 48
        $offset = ($page - 1) * $limit;

        // 5. On récupère les objets pour la page actuelle.
        $objets = $objetRepository->findAllWithSerie(
            $limit,
            $offset
        );

        
        // 6. On compte le nombre total d'objets en base.
        $totalObjets = $objetRepository->countAllActive();
        
        // 7. On calcule le nombre total de pages.
        $totalPages = (int) ceil($totalObjets / $limit);
        
        // 8. Si quelqu'un demande une page trop grande,
        // on peut le ramener à la dernière page existante.
        if ($totalPages > 0 && $page > $totalPages) {
            return $this->redirectToRoute('app_gallery', [
                'page' => $totalPages,
                ]);
        }

                
        // 9. On envoie les données au template Twig.
        return $this->render('gallery/index.html.twig', [
            'objets' => $objets,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            
        ]);
    }
}