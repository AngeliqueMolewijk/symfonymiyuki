<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Form\CategoryType;
use App\Form\ProjectType;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/project', name: 'app_project_')]
final class ProjectController extends AbstractController
{
    // Displays all projects on the project index page

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository, UserInterface $user): Response
    {
        $projects = $projectRepository->findBy(['user' => $user]);
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }


    // Handles displaying and submitting the form for creating a new category

    #[Route('/category', name: 'category', methods: ['GET', 'POST'])]
    public function category(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_project_category', [], Response::HTTP_SEE_OTHER);
        }
        $categories = $categoryRepository->findAll();

        return $this->render('project/category.html.twig', [
            'categories' => $categories,
            'form' => $categoryForm,
        ]);
    }
    // Edits an existing category by its ID

    #[Route('/category/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function categoryedit(Category $category, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_project_category', [], Response::HTTP_SEE_OTHER);
        }
        $categories = $categoryRepository->findAll();

        return $this->render('project/category.html.twig', [
            'categories' => $categories,
            'form' => $categoryForm,
        ]);
    }

    // Creates a new project with optional image upload

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newproject(Request $request, EntityManagerInterface $em, ImageUploaderService $imageUploader): Response
    {
        $user = $this->getUser();
        $project = new Project();

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
             $uploadedFile = $projectForm['imageFile']->getData();
            if ($uploadedFile) {
                $filename = $imageUploader->upload($uploadedFile);
                $project->setImage($filename);
            }
            $project->setUser($user);
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/newproject.html.twig', [
            'form' => $projectForm,


        ]);
    }
    /*
     Edits an existing project and optionally replaces its image
    */

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $em, ImageUploaderService $imageUploader): Response
    {

        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $uploadedFile = $projectForm['imageFile']->getData();
            if ($uploadedFile) {
                $filename = $imageUploader->upload($uploadedFile);

                $project->setImage($filename);
            }
            $em->flush();
            return $this->redirectToRoute('app_project_index', ['id' => $project->getId()]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $projectForm,

        ]);
    }
    // Deletes a project by ID (with CSRF token protection)

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($project);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_project_index'));
    }
}
