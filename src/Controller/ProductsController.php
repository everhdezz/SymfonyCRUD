<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{ Response, Request, ResponseHeaderBag };
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\{ Spreadsheet, Writer\Xlsx };
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
                ->addOrderBy('p.' . $request->query->get('sort_by', 'active'), $request->query->get('sort_type', 'desc'))
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

            $this->addFlash('message', [
                'type' => 'success', 'message' => 'Se creó el registro exitosamente!'
            ]);

            return $this->redirectToRoute('products.show', [ 'id' => $products->getId() ]);
        }

        return $this->renderForm('products/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/products/export", name="products.export")
     */
    public function export(ProductRepository $repository): Response
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Products');

        // Add Headers
        $sheet->getCell('A1')->setValue('Id');
        $sheet->getCell('B1')->setValue('Categoria');
        $sheet->getCell('C1')->setValue('Codigo');
        $sheet->getCell('D1')->setValue('Nombre');
        $sheet->getCell('E1')->setValue('Descripción');
        $sheet->getCell('F1')->setValue('Marca');
        $sheet->getCell('G1')->setValue('Status');
        $sheet->getCell('H1')->setValue('Fecha de creación');
        $sheet->getCell('I1')->setValue('Ultima edición');

        // Add rows
        $sheet->fromArray($repository->getData(),null, 'A2', true);
        
        $writer = new Xlsx($spreadsheet);

        $temp_file = tempnam(sys_get_temp_dir(), 'products.xlsx');
        
        $writer->save($temp_file);
        
        return $this->file($temp_file, 'products.xlsx', ResponseHeaderBag::DISPOSITION_INLINE);
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

            $this->addFlash('message', [
                'type' => 'success', 'message' => 'Se editó el registro exitosamente!'
            ]);

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

        $this->addFlash('message', [
            'type' => 'success', 'message' => 'Se eliminó el registro exitosamente!'
        ]);

        return $this->redirectToRoute('products.index');
    }
}
