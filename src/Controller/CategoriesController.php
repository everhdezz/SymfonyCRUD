<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{ Response, Request };
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories.index")
     */
    public function index(CategoryRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');

        $pagination = $paginator->paginate(
            $repository->search($q)
                ->addOrderBy('c.active', 'DESC')
                ->addOrderBy('c.createdAt', 'DESC'), // Query Builder
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),
        );

        return $this->render('categories/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/categories/create", name="categories.new")
     */
    public function new(CategoryRepository $repository, Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category)
                        ->add('Crear', SubmitType::class, [
                            'attr' => [ 'class' => 'ml-auto btn btn-primary'],
                            'row_attr' => [ 'class' => 'd-flex'],
                        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($category);

            return $this->redirectToRoute('categories.show', [ 'id' => $category->getId() ]);
        }

        return $this->renderForm('categories/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/categories/{id}", name="categories.show")
     */
    public function show(CategoryRepository $repository,  Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category)
                        ->add('Guardar', SubmitType::class, [
                            'attr' => [ 'class' => 'ml-auto btn btn-success'],
                            'row_attr' => [ 'class' => 'd-flex'],
                        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($category);

            return $this->redirectToRoute('categories.show', [ 'id' => $category->getId() ]);
        }

        return $this->renderForm('categories/show.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }

    /**
     * @Route("/categories/{id}/delete", name="categories.delete")
     */
    public function delete(CategoryRepository $repository,  Category $category): Response
    {
        $repository->remove($category);

        return $this->redirectToRoute('categories.index');
    }
}
