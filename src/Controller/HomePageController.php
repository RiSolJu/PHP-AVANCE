<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Film;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class HomePageController extends AbstractController
{
    /**
     * @Route("/homepage", name="home_page")
     */

    public function show(ManagerRegistry $doctrine): Response
    {
        //$films = $doctrine->getRepository(Film::class)->findAll();
        $films = $doctrine->getRepository(Film::class)->findBy(array(), ['score' => 'DESC','NomFilm'=> 'ASC']);
        

        if (!$films) {
            throw $this->createNotFoundException(
                'No product found.'
            );
        }

        //return new Response('Check out those great products: '.$films);
        return $this->render('home_page/index.html.twig', ['films' => $films, 'controller_name' => 'HomePageController']);
        // or render a template
        // in the template, print things with {{ product.name }}
        // ;
    }


}
