<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    #[Route('/books/{id}', name: 'app_books')]
    public function index(int $id, ProductRepository $productRepository): Response
    {
        $book = $productRepository->findOneBy(['id' => $id]);

        return $this->render('book/index.html.twig', [
            'book' => $book,
        ]);
    }
}
