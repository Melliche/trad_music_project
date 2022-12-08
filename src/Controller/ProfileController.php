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

    #[Route('/profil/{id}', name: 'app_profile',requirements: ['id' => '\d+'])]
    public function profile(int $id,MusicianRepository $musicianRepository,FileUploader $uploader,\Symfony\Component\HttpFoundation\Request $request,EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(ProfileFormType::class, $musicianRepository->find($id));
        $form->handleRequest($request);
        $musician = $musicianRepository->find($id);


        // Uploader l'image
        $image = $form->get('image')->getData();
        if ($image) {
            $fileName = $uploader->upload($image);
            $musician->setImage($fileName);
        }
        $entityManager->persist($musician);
        $entityManager->flush();








        return $this->render('profile/index.html.twig', [
            'musician' => $musician,
            'profileForm' => $form->createView(),

        ]);






    }




}
