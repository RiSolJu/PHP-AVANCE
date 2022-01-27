<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Serializer\SerializerInterface;


class AddFilmCSVController extends AbstractController
{
    /**
     * @Route("/addFilmCSV", name="addFilmCSV")
     */
    public function index(ManagerRegistry $doctrine,SerializerInterface $serializer, Request $request): Response
    {

        $CSVforms = $this->createFormBuilder()
        ->add('FileFilm', FileType::class,[ 'constraints' => [
            new File(['mimeTypes' => ['text/plain'],'mimeTypesMessage' => 'Please upload a valid CSV document'])]
            ])
        ->add('save', SubmitType::class,['label' => 'Entrer un fichier de Films'])
        ->getForm();

        $CSVforms->handleRequest($request);
        if ($CSVforms->isSubmitted() && $CSVforms->isValid() ){
            $entityManager = $doctrine->getManager();
            $File = $CSVforms['FileFilm']->getdata();
            var_dump(file_get_contents($File->getPathname()));
            
            $films = $serializer->deserialize(file_get_contents($File->getPathname()), 'App\Entity\Film[]', 'csv');
            var_dump($films);
            foreach ($films as $film)
            {
                $entityManager->persist($film);
            }
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }

        return $this->render('add_film_csv/index.html.twig', ['formfile' => $CSVforms->createView(),
        ]);

    }
}
