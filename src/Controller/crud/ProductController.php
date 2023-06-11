<?php

namespace App\Controller\crud;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'crud_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('crud/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'crud_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medias = $form->get('medias')->getData();
            $stock = $form->get('stock')->getData();

            foreach ($medias as $media) {
//                $product->addMedia($media);
                $media->setProduct($product);
            }

            $product->setAvailable($stock > 0);

            $productRepository->save($product, true);

            return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'crud_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('crud/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'crud_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $oldMedias = $product->getMedias()->toArray();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medias = $form->get('medias')->getData()->toArray();
            $stock = $form->get('stock')->getData();

            foreach ($oldMedias as $oldMedia) {
                if (!in_array($oldMedia, $medias)) {
                    $oldMedia->setProduct(null);
                }
            }

            foreach ($medias as $media) {
                if (!in_array($media, $oldMedias)) {
                    $media->setProduct($product);
                }
            }

            $product->setAvailable($stock > 0);

            $productRepository->save($product, true);

            return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'crud_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
