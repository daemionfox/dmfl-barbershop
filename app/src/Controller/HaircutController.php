<?php

namespace App\Controller;

use App\Entity\Cadet;
use App\Entity\Haircut;
use App\Enumerations\StatusEnumeration;
use App\Form\HaircutFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HaircutController extends AbstractController
{

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

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pendingCuts = $entityManager->getRepository(Haircut::class)->findPendingHaircuts();

        return $this->render('dashboard.html.twig', ['haircuts' => $pendingCuts]);

    }

}