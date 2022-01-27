<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;



class AddFilmController extends AbstractController
{
    /**
     * @Route("/addFilm", name="add_Film")
     */

    public function new(ManagerRegistry $doctrine,SerializerInterface $serializer, Request $request): Response
    {

        $form = $this->createFormBuilder()
            ->add('NomFilm', TextType::class)
            ->add('score', NumberType::class)
            ->add('Email', EmailType::class)
            ->add('save', SubmitType::class, ['label' => 'Ajouter le Film'])
            ->getForm();




  
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() && $form['score']->getdata() != null) {
            $film = new Film();
            $film->setNomFilm($form['NomFilm']->getdata());
            $film->setScore($form['score']->getdata());
            
            $entityManager = $doctrine->getManager();
            
            $entityManager->persist($film);
            
            $entityManager->flush();
            
            return $this->redirectToRoute('home_page');
        }



        return $this->render('add_film/index.html.twig', [
            'form' => $form->createView(), 
        ]);
    }
}

