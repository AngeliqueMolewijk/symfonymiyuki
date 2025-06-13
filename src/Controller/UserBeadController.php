<?php

namespace App\Controller;

use App\Entity\Bead;
use App\Entity\User;
use App\Entity\UserBead;
use App\Form\BeadToMixType;
use App\Form\BeadType;
use App\Form\UserBeadType;
use App\Repository\UserBeadRepository;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/userbead', name: 'app_userbead_')]

final class UserBeadController extends AbstractController
{

    #[Route('/{id}/remove', name: 'remove')]

    public function remove(Bead $bead, EntityManagerInterface $em, UserBeadRepository $userBeadRepository): Response
    {
        $user = $this->getUser();
        $relatedBeads = $bead->getComponents();
        $id = $bead->getId();
        $userBead = $em->getRepository(UserBead::class)->findOneBy([
            'user' => $user,
            'bead' => $bead,
        ]);

        if ($userBead) {
            $em->remove($userBead);
            $em->flush();
        }
        foreach ($relatedBeads as $component) {
            $userBead = $em->getRepository(UserBead::class)->findOneBy([
                'user' => $user,
                'bead' => $component,
            ]);
            if ($userBead->getStock() > 0) {
                # code...
            } else {
                $em->remove($userBead);
                $em->flush();
            }
            // dd($component);
            // dd($userBead);
            // $bead->addComponent($component);


        }

        return $this->redirect($this->generateUrl('app_bead_homepage'));
    }

    #[Route('/{id}/add', name: 'add')]
    public function add(Bead $bead, EntityManagerInterface $em, UserInterface $user)
    {
        // $user =
        $id = $bead->getId();
        // Try to find an existing product by name
        $alreadyproduct = $em->getRepository(UserBead::class)->findOneBy([
            'user' => $user,
            'bead' => $id,
        ]);
        $relatedBeads = $bead->getComponents();

        if (!$alreadyproduct) {

            $userBead = new UserBead();
            $userBead->setUser($user);
            $userBead->setBead($bead);

            $em->persist($userBead);
            $em->flush();
        }
        foreach ($relatedBeads as $component) {

            // $bead->addComponent($component);
            $id = $component->getId();
            // dd($id);
            $alreadyproduct = $em->getRepository(UserBead::class)->findOneBy([
                'user' => $user,
                'bead' => $id,
            ]);
            if (!$alreadyproduct) {

                $userBead = new UserBead();
                $userBead->setUser($user);
                $userBead->setBead($component);

                $em->persist($userBead);
                $em->flush();
            }
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        } else {
            return $this->redirectToRoute('app_bead_show', ['id' => $bead->getId()]);
        }
    }

    #[Route('/{id}/addComponents', name: 'addComponents')]
    public function addComponents(Bead $bead, EntityManagerInterface $em, UserInterface $user)
    {
        $id = $bead->getId();


        $relatedBeads = $bead->getComponents();
        // dd($relatedBeads);
        // Try to find an existing product by name
        $alreadyproduct = $em->getRepository(UserBead::class)->findOneBy([
            'user' => $user,
            'bead' => $id,
        ]);

        foreach ($relatedBeads as $component) {

            $bead->addComponent($component);

            if (!$alreadyproduct) {

                $userBead = new UserBead();
                $userBead->setUser($user);
                $userBead->setBead($component);

                $em->persist($userBead);
                $em->flush();
            }
        }

        return $this->redirectToRoute('app_bead_showt', ['id' => $bead->getId()]);
    }



    #[Route('/{id}/editstock', name: 'editstock', methods: ['GET', 'POST'])]
    public function editstock(Request $request, Bead $bead, EntityManagerInterface $em, UserBeadRepository $userBeadRepository): Response
    {
        $user = $this->getUser();

        $userBead = $userBeadRepository->findOneBy([
            'user' => $user,
            'bead' => $bead,
        ]);

        $userBeadForm = $this->createForm(UserBeadType::class, $userBead);
        $userBeadForm->handleRequest($request);

        if ($userBeadForm->isSubmitted()) {
            if ($userBeadForm->isValid()) {

                $em->persist($userBead);
                $em->flush();
                return $this->redirectToRoute('app_bead_show', ['id' => $userBead->getBead()->getId()]);
            }
        }
        return $this->render('bead/editstock.html.twig', [
            'bead' => $userBead,
            'beadForm' => $userBeadForm,
        ]);
    }
}
