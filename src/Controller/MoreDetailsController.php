<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class MoreDetailsController extends AbstractController
{
    /**
     * @Route("/moredetails/{idFilm}", name="more_details")
     */
    public function showdetails(ManagerRegistry $doctrine, int $idFilm, Request $request): Response
    {
        $film = $doctrine->getRepository(Film::class)->find($idFilm);

        $form = $this->createFormBuilder()
            ->add('Admincode', PasswordType::class, ['label' => 'Code admin'])
            ->add('save', SubmitType::class, ['label' => 'Supprimer le Film'])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form['Admincode']->getdata() == $this->getParameter('KEY') ){
                $entityManager = $doctrine->getManager();

                $entityManager->remove($film);
                $entityManager->flush();

                return $this->redirectToRoute('home_page');
            }
            return $this->renderForm('more_details/index.html.twig', ['film' => $film, 'controller_name' => 'MoreDetailsController', 'form' => $form,'badPassword' =>  'true']);
        }

        return $this->renderForm('more_details/index.html.twig', ['film' => $film, 'controller_name' => 'MoreDetailsController', 'form' => $form, 'badPassword' =>  'false']);

        throw $this->createNotFoundException(
            'No product found.'
        );
       

    }
}
