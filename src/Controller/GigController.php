<?php

namespace App\Controller;

use App\Entity\Gig;
use App\Entity\Instrument;
use App\Entity\Musician;
use App\Entity\Participant;
use App\Repository\GigRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GigController extends AbstractController
{
    #[Route('/gig/{id}', name: 'gig_detail')]
    public function detail(Gig $gig): Response
    {
        return $this->render('gig/detail.html.twig', [
            'gig' => $gig,
        ]);
    }
    #[Route('/gig/addMusician/{id}/{gigInstrumentId}', name: 'app_add_musician')]
    public function addMusician(
        int $id,
        int $gigInstrumentId,
        GigRepository $gigRepository,
        UserInterface $user,
        MusicianRepository $musicianRepository,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        InstrumentRepository $instrumentRepository,
    ): Response
    {
        $musician = $musicianRepository->findOneBy(['email'=> $user->getUserIdentifier()]);
        $gig = $gigRepository->find($id);
        $gigInstrument = $instrumentRepository->find($gigInstrumentId);
        $checkInstrument = false;
        $matchedInstrument = "";
        $musicianInstruments = $musician->getInstruments();
        foreach ($musicianInstruments as $mInstrument) {
            if ($gigInstrument && $mInstrument->getName() === $gigInstrument->getName()) {
                $checkInstrument = true;
                $matchedInstrument = $mInstrument;
                break;
            }
            $checkInstrument = false;
            $matchedInstrument = "";
        }

        if ($checkInstrument && $matchedInstrument) {
            //checker si le current user possède l'instrument requis par le gig
            $participant = $participantRepository->findOneBy([ 'gig' => $gig, 'instrument' => $matchedInstrument]);

            if ($participant) {
                $participant->setMusician($musician);
                $entityManager->flush();
            }
            //$entityManager->persist($participant); object déjà en db

            return $this->render('gig/addMusician.html.twig', [
                'gig' => $gig,
                'user' => $user,
                'musician' => $musician,
                'gigInstrument' => $gigInstrument,
            ]);
        }
        return $this->redirectToRoute('homepage');
    }
    /*public function checkMusicianGotGigsInstrument(Gig $gig, Musician $musician): bool
    {

    }*/
}
