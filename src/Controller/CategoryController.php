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

#[Route('/category', name: 'app_category_')]
final class CategoryController extends AbstractController
{
    // Handles displaying and submitting the form for creating a new category

    #[Route('/index', name: 'index', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function categoryedit(Category $category, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    {
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }
        $categories = $categoryRepository->findAll();

        return $this->render('project/category.html.twig', [
            'categories' => $categories,
            'form' => $categoryForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function categorydelete(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('app_category_index'));
    }
}
