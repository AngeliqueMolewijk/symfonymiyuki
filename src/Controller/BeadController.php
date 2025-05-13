<?php

namespace App\Controller;

use App\Entity\Bead;
use App\Entity\User;
use App\Entity\UserBead;
use App\Form\BeadToMixType;
use App\Form\BeadType;
use App\Repository\BeadRepository;
use App\Repository\UserBeadRepository;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/bead', name: 'app_bead_')]

final class BeadController extends AbstractController
{
    #[Route('', name: 'homepage')]
    public function homepage(BeadRepository $beadRepository, Request $request, UserInterface $user): Response
    {
        return $this->renderBeadList('bead/homepage.html.twig', $request, $beadRepository, $user);
    }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(BeadRepository $beadRepository, Request $request, UserInterface $user): Response
    {

        return $this->renderBeadList('bead/index.html.twig', $request, $beadRepository, $user);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ImageUploaderService $imageUploader, UserInterface $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        // $user = $this->getUser();
        $bead = new Bead();
        $userBead = new UserBead();
        $userBead->setUser($user);
        $userBead->setBead($bead); // link it to the new Bead
        $userBead->setStock(0); // default stock value

        $beadForm = $this->createForm(BeadType::class, $bead, [
            'user_bead' => $userBead,
            'show_stock' => $userBead !== null,

        ]);
        $beadForm->handleRequest($request);

        if ($beadForm->isSubmitted() && $beadForm->isValid()) {
            $uploadedFile = $beadForm['imageFile']->getData();
            if ($uploadedFile) {
                $filename = $imageUploader->upload($uploadedFile);
                $bead->setImage($filename);
            }

            if ($beadForm->has('components')) {

                $newComponents = $beadForm->get('components')->getData();
                foreach ($newComponents as $component) {
                    $bead->addComponent($component);
                }
            }
            $em->persist($bead);
            $em->persist($userBead);
            $em->flush();

            return $this->redirectToRoute('app_bead_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bead/new.html.twig', [
            'form' => $beadForm,
        ]);
    }

    // Shows bead details and allows adding it to a mix

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    public function show(Bead $bead, Request $request, EntityManagerInterface $em): Response
    {
        $beadToMixForm = $this->createForm(BeadToMixType::class, $bead);
        $beadToMixForm->handleRequest($request);

        if ($beadToMixForm->isSubmitted() && $beadToMixForm->isValid()) {
            $em->persist($bead);
            $em->flush();
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        }
        return $this->render('bead/show.html.twig', [
            'bead' => $bead,
            'beadToMixForm' => $beadToMixForm->createView(),
        ]);
    }
    /*
     Edits an existing bead and handles optional image replacement
    */

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Bead $bead, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bead->getId(), $request->request->get('_token'))) {
            $bead->setDeletedAt(new \DateTime());
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_bead_homepage'));
    }


    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(UserBeadRepository $userBeadRepository, Request $request, Bead $bead, EntityManagerInterface $em, ImageUploaderService $imageUploader): Response
    {
        $user = $this->getUser();
        $userBead = $userBeadRepository->findOneBy([
            'user' => $user,
            'bead' => $bead,
        ]);


        $beadForm = $this->createForm(BeadType::class, $bead, [
            'user_bead' => $userBead,
            'show_stock' => $userBead !== null,


        ]);

        $beadForm->handleRequest($request);
        if ($beadForm->isSubmitted() && $beadForm->isValid()) {
            $uploadedFile = $beadForm['imageFile']->getData();
            if ($uploadedFile) {
                $filename = $imageUploader->upload($uploadedFile);
                $bead->setImage($filename);
            }
            if ($userBead !== null) {
                $userBeadData = $beadForm->get('userBead')->getData();
                $userBead->setStock($userBeadData->getStock());
            }
            $em->flush();
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        }

        return $this->render('bead/edit.html.twig', [
            'bead' => $bead,
            'form' => $beadForm,
        ]);
    }


    private function renderBeadList(string $template, Request $request, BeadRepository $beadRepository, UserInterface $user): Response
    {
        // $user = $this->getUser();
        $query = $request->query->get('q');

        $beads = $beadRepository->findSearchBeads($query, $user);

        return $this->render($template, [
            'beads' => $beads,
        ]);
    }
}
