<?php

namespace App\Controller;

use App\Repository\MusicianRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profile',requirements: ['id' => '\d+'])]
    public function profile(int $id,MusicianRepository $musicianRepository): Response
    {
        $musician = $musicianRepository->find($id);

        return $this->render('profile/index.html.twig', [
            'musician' => $musician,

        ]);







    }
}
