<?php

namespace App\Controller;

use App\Entity\Cadet;
use App\Entity\Haircut;
use App\Enumerations\StatusEnumeration;
use App\Form\HaircutFormType;
use App\Form\HaircutStartFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HaircutController extends AbstractController
{


    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pendingCuts = $entityManager->getRepository(Haircut::class)->findPendingHaircuts();
        return $this->render('dashboard.html.twig', ['haircuts' => $pendingCuts]);
    }



    #[Route('/start/{id}', name: 'app_start')]
    public function start(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $haircut = $entityManager->getRepository(Haircut::class)->find($id);
        $form = $this->createForm(HaircutStartFormType::class, $haircut);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $haircut->setStatus(StatusEnumeration::STATUS_STARTED);
            $entityManager->persist($haircut);
            $entityManager->flush();
            return new RedirectResponse($this->generateUrl('app_dashboard'));
        }

        return $this->render('start.html.twig', [ "cadet" => [ "name" => $haircut->getCadetname(), "badge" => $haircut->getCadetbadge()] , "startform" => $form->createView() ]);


    }

    #[Route('/cancel/{id}', name: 'app_cancel')]
    public function cancel(EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $haircut = $entityManager->getRepository(Haircut::class)->find($id);
        $haircut->setStatus(StatusEnumeration::STATUS_CANCELLED);
        $entityManager->persist($haircut);
        $entityManager->flush();
        return new RedirectResponse($this->generateUrl('app_dashboard'));
    }


    #[Route('/noshow/{id}', name: 'app_noshow')]
    public function nowshow(EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $haircut = $entityManager->getRepository(Haircut::class)->find($id);
        $haircut->setStatus(StatusEnumeration::STATUS_NOSHOW);
        $entityManager->persist($haircut);
        $entityManager->flush();
        return new RedirectResponse($this->generateUrl('app_dashboard'));
    }

    #[Route('/complete/{id}', name: 'app_complete')]
    public function complete(Request $request, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $haircut = $entityManager->getRepository(Haircut::class)->find($id);
        $haircut->setStatus(StatusEnumeration::STATUS_COMPLETE)->setEndtime(new \DateTime());
        $entityManager->persist($haircut);
        $entityManager->flush();
        return new RedirectResponse($this->generateUrl('app_dashboard'));
    }


    #[Route('/', name: 'app_index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $haircut = new Haircut();
        $form = $this->createForm(HaircutFormType::class, $haircut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Fill out the haircut entity and submit
            try {
                $dupe = $entityManager->getRepository(Haircut::class)->findCurrentActiveHaircuts($haircut->getCadetbadge());
                if (!empty($dupe)) {
                    throw new \Exception("Cadet is already in the queue.");
                }
                $cadet = $entityManager->getRepository(Cadet::class)->findOneBy(['vmibadgeid' => $haircut->getCadetbadge()]);
                if (empty($cadet)) {
                    throw new \Exception("Cadet is not in the database.");
                }
                $haircut->setCadetname($cadet->getName())->setStatus(StatusEnumeration::STATUS_WAITING);
                $entityManager->persist($haircut);
                $entityManager->flush();
                $this->addFlash("success",  "Thank you.  Please proceed to the waiting area until your name is called.");

            } catch (\Exception $e) {
                $this->addFlash("error", $e->getMessage());
            }
        }
        $haircut = new Haircut();
        $form = $this->createForm(HaircutFormType::class, $haircut);
        return $this->render('index.html.twig', ['cadetform' => $form->createView()]);
    }
}