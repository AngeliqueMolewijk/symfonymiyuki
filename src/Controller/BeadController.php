<?php

namespace App\Controller;

use App\Entity\Bead;
use App\Form\BeadToMixType;
use App\Form\BeadType;
use App\Repository\BeadsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class BeadController extends AbstractController
{
    /*
     * Displays the homepage with a search bar and stock filter for beads.
     * The mix and stock buttons are also available at the top of the page.
     */
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

        return $this->render('bead/index.html.twig', [
            'beads' => $beadsRepository->findAll(),
        ]);
    }
    /*
    TODO seperate the uploadFile in a ImageUploaderService 
    TODO: separate this from the bead entity and make a manyToMany relation.
        for every user there own beads
    */

    #[Route('/newbead', name: 'app_bead_new', methods: ['GET', 'POST'])]
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
            // add the userid

            $bead->setUserid(2);
            if ($beadForm->has('components')) {

                $newComponents = $beadForm->get('components')->getData();
                foreach ($newComponents as $component) {
                    $bead->addComponent($component);
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

    // Shows bead details and allows adding it to a mix

    #[Route('bead/{id}', name: 'app_bead_show', methods: ['GET', 'POST'])]
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
    TODO seperate the uploadFile in a ImageUploaderService 
    */

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
    // Deletes a project by ID (with CSRF token protection)

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
