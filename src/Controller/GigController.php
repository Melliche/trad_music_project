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
    #[Route('/gig/addMusician/{id}', name: 'app_add_musician')]
    public function addMusician(
        int $id,
        GigRepository $gigRepository,
        UserInterface $user,
        MusicianRepository $musicianRepository,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
    ): Response
    {

        $musician = $musicianRepository->findOneBy(['email'=> $user->getUserIdentifier()]);
        $gig = $gigRepository->find($id);
        $checkInstrument = false;
        $matchedInstrument = "";
        $matchedInstrumentId = null;
        $gigParticipants = $gig->getParticipants();
        foreach ($gigParticipants as $participant) {
            $musicianInstruments = $musician->getInstruments();
            foreach ($musicianInstruments as $mInstrument) {
                if ($mInstrument->getName() === $participant->getInstrument()->getName()) {
                    $checkInstrument = true;
                    $matchedInstrumentId = $mInstrument->getId();
                    $matchedInstrument = $mInstrument;
                }
            }
        }

        if ($checkInstrument && $matchedInstrument && $matchedInstrumentId) {
            //checker si le current user possède l'instrument requis par le gig
            $participant = $participantRepository->findBy([ Gig::class => $gig, InstrumentRepository::class => $matchedInstrument]);
            $participant->setMusician($musician);
            var_dump($musician->getId());
            $participant->setInstrument($matchedInstrument);
            $participant->setGig($gig);

            $entityManager->persist($participant);
            $entityManager->flush();
        }
        return $this->render('gig/addMusician.html.twig', [
            'gig' => $gig,
            'user' => $user,
            'musician' => $musician,
        ]);
    }
    /*public function checkMusicianGotGigsInstrument(Gig $gig, Musician $musician): bool
    {

    }*/
}
