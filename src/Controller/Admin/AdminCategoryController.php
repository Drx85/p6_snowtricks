<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class AdminCategoryController extends AbstractController
{
	/**
	 * @Route("/", name="admin.category.index", methods={"GET"})
	 * @param CategoryRepository $categoryRepository
	 *
	 * @return Response
	 */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
	
	/**
	 * @Route("/new", name="admin.category.new", methods={"GET","POST"})
	 * @param Request $request
	 *
	 * @return Response
	 */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
			$this->addFlash('success', 'Catégorie créée avec succès.');
            return $this->redirectToRoute('admin.category.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }
	
	/**
	 * @Route("/{id}/edit", name="admin.category.edit", methods={"GET","POST"})
	 * @param Request  $request
	 * @param Category $category
	 *
	 * @return Response
	 */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
			$this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.category.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin.category.edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }
	
	/**
	 * @Route("/{id}", name="admin.category.delete", methods={"POST"})
	 * @param Request  $request
	 * @param Category $category
	 *
	 * @return Response
	 */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }
		$this->addFlash('success', 'Catégorie supprimée avec succès.');
        return $this->redirectToRoute('admin.category.index', [], Response::HTTP_SEE_OTHER);
    }
}
