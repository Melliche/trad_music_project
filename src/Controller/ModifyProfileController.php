<?php

namespace App\Controller;

use App\Form\ModifyNameType;
use App\Repository\MusicianRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Musician;
use Symfony\Component\Security\Core\User\UserInterface;

class ModifyProfileController extends AbstractController
{
    #[Route('/modify_profile', name: 'modify_profile')]
    public function modify(Request $request,FileUploader $fileUploader, MusicianRepository $musicianRepository,EntityManagerInterface $entityManager,UserInterface $user): Response
    {
        $musician = $musicianRepository->findOneBy(['email'=> $musicianRepository->getClassName()]);


        $form = $this->createForm(ModifyNameType::class, $musician);

        $form->handleRequest($request);
         //Upload de l'image
        if ($form->isSubmitted() && $form->isValid()) {


            $image = $form->get('image')->getData();
            if ($image) {
                $fileName = $fileUploader->upload($image);
                $user->setImage($fileName);

                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_profile');

            }



            }








        return $this->render('modify_profile/index.html.twig', [
            'modifyForm' => $form->createView(),

        ]);




    }
}
