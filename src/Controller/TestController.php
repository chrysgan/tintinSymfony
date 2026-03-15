<?php

namespace App\Controller;

use App\Repository\EditeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EditeurRepository $editeurRepository): Response
    {
        $editeurs = $editeurRepository->createQueryBuilder('e')
            ->join('e.idpays', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();

        $html = "<table border='1'>";
        $html .= "<tr><th>Editeur</th><th>Pays (alpha2)</th></tr>";

        foreach ($editeurs as $editeur) {
            $html .= "<tr>";
            $html .= "<td>".$editeur->getNom()."</td>";
            $html .= "<td>".$editeur->getIdpays()->getAlpha2()."</td>";
            $html .= "</tr>";
        }

        $html .= "</table>";

        return new Response($html);
    }
}
