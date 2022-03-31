<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{ Response, Request };
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use App\Entity\Product;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products.index")
     */
    public function index(ProductRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');

        $pagination = $paginator->paginate(
            $repository->search($q)
                ->addOrderBy('p.active', 'DESC')
                ->addOrderBy('p.createdAt', 'DESC'), // Query Builder
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),
        );

        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/products/create", name="products.new")
     */
    public function new(ProductRepository $repository, Request $request): Response
    {
        $products = new Product();

        $form = $this->createForm(ProductType::class, $products)
                        ->add('Crear', SubmitType::class, [
                            'attr' => [ 'class' => 'ml-auto btn btn-primary'],
                            'row_attr' => [ 'class' => 'd-flex'],
                        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($products);

            return $this->redirectToRoute('products.show', [ 'id' => $products->getId() ]);
        }

        return $this->renderForm('products/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/products/{id}", name="products.show")
     */
    public function show(ProductRepository $repository,  Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product)
                        ->add('Guardar', SubmitType::class, [
                            'attr' => [ 'class' => 'ml-auto btn btn-success'],
                            'row_attr' => [ 'class' => 'd-flex'],
                        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($product);

            return $this->redirectToRoute('products.show', [ 'id' => $product->getId() ]);
        }

        return $this->renderForm('products/show.html.twig', [
            'form' => $form,
            'product' => $product
        ]);
    }

    /**
     * @Route("/products/{id}/delete", name="products.delete")
     */
    public function delete(ProductRepository $repository,  Product $product): Response
    {
        $repository->remove($product);

        return $this->redirectToRoute('products.index');
    }
}
