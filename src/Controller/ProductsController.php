<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{ Response, Request };
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use App\Entity\Product;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products.index")
     */
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }

    /**
     * @Route("/products/create", name="products.new")
     */
    public function new(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product)->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            dd($task);
        }

        return $this->renderForm('products/new.html.twig', [
            'form' => $form,
        ]);
    }
}
