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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{

    #[Route('/profil', name: 'app_profile')]
    public function profile( MusicianRepository $musicianRepository,UserInterface $user): Response
    {

        {

           $musician = $musicianRepository->findOneBy(['email'=> $user->getUserIdentifier()]);







            return $this->render('profile/index.html.twig', ['musician' => $musician]);


        }
    }
}




