<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Omdbapi;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchFilmController extends AbstractController
{
    /**
     * @Route("/searchFilm", name="search_film")
     */
    public function research(Omdbapi $Omdbapi, Request $request): Response
    {
        $formAPI = $this->createFormBuilder()
        ->add('NomFilm', TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Chercher un film'])
        ->getForm();
    
    
        $formAPI->handleRequest($request);
        if ($formAPI->isSubmitted() && $formAPI->isValid()) {
            $APIFilmInfos = $Omdbapi->FilmAPI($formAPI['NomFilm']->getdata());
            return $this->render('search_film/recherche.html.twig', $APIFilmInfos);
        }
        
        return $this->render('search_film/index.html.twig', [
            'formrecherche' => $formAPI->createView(), 
        ]);
    }


}
