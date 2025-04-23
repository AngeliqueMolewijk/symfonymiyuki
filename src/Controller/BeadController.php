<?php

namespace App\Controller;

use App\Entity\Bead;
use App\Form\BeadToMixType;
use App\Form\BeadType;
use App\Form\RelatedBeadsType;
use App\Repository\BeadsRepository;
use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

// #[Route('/bead', name: 'bead_')]

final class BeadController extends AbstractController
{

    #[Route('/bead', name: 'homepage')]
    public function homepage(BeadsRepository $beadRepository, Request $request, UserInterface $user): Response
    {

        $beads = $beadRepository->findSearchBeads(
            $request->query->get('q'),
            $request->query->get('stock'),
            $user

        );
        return $this->render('bead/homepage.html.twig', ['beads' => $beads]);
    }

    #[Route('/list', name: 'app_bead_index', methods: ['GET'])]
    public function index(BeadsRepository $beadsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('bead/index.html.twig', [
            'beads' => $beadsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bead_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $bead = new Bead();
        $beadForm = $this->createForm(BeadType::class, $bead);
        $beadForm->handleRequest($request);

        if ($beadForm->isSubmitted() && $beadForm->isValid()) {
            $uploadedFile = $beadForm['imageFile']->getData();
            if (isset($uploadedFile)) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/images';
                $newFilename = date('YmdHi') . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $bead->setImage($newFilename);
            } else {
                $bead->setImage("noimage.jpeg");
            }
            $bead->setUserid(2);
            if ($beadForm->has('components')) {

                $newComponents = $beadForm->get('components')->getData();
                foreach ($newComponents as $component) {
                    // if (!$originalComponents->contains($component)) {
                    $bead->addComponent($component);
                    // }
                }
            }
            $em->persist($bead);
            $em->flush();

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bead/new.html.twig', [
            'beadForm' => $beadForm,
            'form' => $beadForm,
        ]);
    }

    #[Route('bead/{id}', name: 'app_bead_show', methods: ['GET', 'POST'])]
    public function show(Bead $bead, Request $request, EntityManagerInterface $em): Response
    {

        $originalComponents = new ArrayCollection($bead->getComponents()->toArray());
        $beadToMixForm = $this->createForm(BeadToMixType::class, $bead);

        $beadToMixForm->handleRequest($request);
        // dd($beadToMixForm->get('components'));

        if ($beadToMixForm->isSubmitted() && $beadToMixForm->isValid()) {
            if ($beadToMixForm->has('components')) {

                /** @var Bead $submittedBead */
                $newComponents = $beadToMixForm->get('components')->getData();

                foreach ($newComponents as $component) {
                    if (!$originalComponents->contains($component)) {
                        $bead->addComponent($component);
                    }
                }
            }
            $em->persist($bead);
            $em->flush();
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        }

        $inmix = $bead->getUsedInMixes();
        return $this->render('bead/show.html.twig', [
            'bead' => $bead,
            'inmix' => $inmix,
            'beadToMixForm' => $beadToMixForm->createView(),
        ]);
    }

    #[Route('bead/{id}/edit', name: 'app_bead_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bead $bead, EntityManagerInterface $em): Response
    {

        $beadForm = $this->createForm(BeadType::class, $bead);
        $beadForm->handleRequest($request);
        if ($beadForm->isSubmitted() && $beadForm->isValid()) {
            $uploadedFile = $beadForm['imageFile']->getData();
            if (isset($uploadedFile)) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/images';

                $newFilename = date('YmdHi') . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $bead->setImage($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        }

        return $this->render('bead/edit.html.twig', [
            'bead' => $bead,
            'beadForm' => $beadForm,
        ]);
    }

    #[Route('delete/{id}', name: 'app_bead_delete', methods: ['POST'])]
    public function testdel(Request $request, Bead $bead, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bead->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($bead);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('homepage'));
    }
}
