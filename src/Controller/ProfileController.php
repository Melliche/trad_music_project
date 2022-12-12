<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Repository\MusicianRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{

    #[Route('/profil/{id}', name: 'app_profile', requirements: ['id' => '\d+'])]
    public function profile(int $id, MusicianRepository $musicianRepository): Response
    {

        {

            $musician = $musicianRepository->find($id);


            // Si le musicien n'existe pas en base de données on retourne une erreur 404
            if ($musician === null) {
                throw $this->createNotFoundException();
            }

            return $this->render('profile/index.html.twig', ['musician' => $musician]);


        }
    }
}




