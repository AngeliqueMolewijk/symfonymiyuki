<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Form\CategoryType;
use App\Form\ProjectType;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{

    #[Route('/project', name: 'app_project', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/category', name: 'app_category', methods: ['GET', 'POST'])]
    public function category(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_category', [], Response::HTTP_SEE_OTHER);
        }
        $categories = $categoryRepository->findAll();

        return $this->render('project/category.html.twig', [
            'categories' => $categories,
            'categoryForm' => $categoryForm,
        ]);
    }

    #[Route('/category{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function categoryedit(Category $category, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_category', [], Response::HTTP_SEE_OTHER);
        }
        $categories = $categoryRepository->findAll();

        return $this->render('project/category.html.twig', [
            'categories' => $categories,
            'categoryForm' => $categoryForm,
        ]);
    }


    #[Route('/newproject', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function newproject(Request $request, EntityManagerInterface $em): Response
    {

        $project = new Project();
        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $uploadedFile = $projectForm['imageFile']->getData();
            if (isset($uploadedFile)) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/images';
                $newFilename = date('YmdHi') . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $project->setImage($newFilename);
            } else {
                $project->setImage("noimage.jpeg");
            }
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/newproject.html.twig', [
            'projectForm' => $projectForm,
        ]);
    }

    #[Route('project/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $em): Response
    {

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $uploadedFile = $projectForm['imageFile']->getData();
            if (isset($uploadedFile)) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/images';

                $newFilename = date('YmdHi') . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $project->setImage($newFilename);
            }
            $em->flush();
            return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'projectForm' => $projectForm,
        ]);
    }

    #[Route('Project/{id}/delete', name: 'app_project_delete', methods: ['POST'])]
    public function testdel(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($project);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_project'));
    }
}
