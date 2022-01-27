<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Film;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;

class AddImageController extends AbstractController
{
    /**
     * @Route("/addImage/{idFilm}", name="add_image")
     */
    public function index(ManagerRegistry $doctrine, int $idFilm, SluggerInterface $slugger,Request $request): Response
    {
        $form = $this->createFormBuilder()
        ->add('fileImage', FileType::class,[ 'constraints' => [
            new File(['mimeTypes' => ['image/png'],'mimeTypesMessage' => 'Please upload a valid png image'])]
            ])
        ->add('save', SubmitType::class, ['label' => 'Ajouter cette image au Film'])
        ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['fileImage']->getdata();
            $entityManager = $doctrine->getManager();
            $film = $entityManager->getRepository(Film::class)->find($idFilm);
    

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            try{
            $image->move(
                'ImageFile',
                $newFilename);
            }
            catch (FileException $e) {
            dd();
            }

                
            if (!$film) {
                throw $this->createNotFoundException(
                    'No product found for id '.$idFilm
                );
            }
    
            $film->setFileName($newFilename);
            $entityManager->flush();
            
            return $this->redirectToRoute('more_details', array('idFilm' => $idFilm));
        }

        return $this->render('add_image/index.html.twig', [
            'controller_name' => 'AddImageController', 
                'form' => $form->createView(), 
            ]
        );
    }
}
