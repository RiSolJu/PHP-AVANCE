<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;
use Doctrine\Persistence\ManagerRegistry;

class SeeChartController extends AbstractController
{
    /**
     * @Route("/seeChart", name="see_chart")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $films = $doctrine->getRepository(Film::class)->findAll();
        $scores = [];
        
        for ($i = 1; $i <= 10; $i++) 
        {
          $filtredArray = array_filter($films, fn($film) => $film->getScore() === $i);
          $countScores = count($filtredArray);
          $scores[] = $countScores;
        }
        return $this->render('see_chart/index.html.twig', [
            'scores' => $scores,
        ]);
    }
}
