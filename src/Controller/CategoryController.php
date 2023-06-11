<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category_index')]
    public function index(): Response
    {
        // redirect to the home page
        return $this->redirectToRoute('app_home');
    }

    #[Route('/category/{category}', name: 'app_category_list')]
    public function list(string $category, CategoryRepository $categoryRepository): Response
    {
        $books = $categoryRepository->findOneBy(['name' => $category])->getProducts();

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'books' => $books
        ]);
    }
}
