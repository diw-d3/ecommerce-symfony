<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(ProductRepository $productRepository)
    {
        $randomProducts = $productRepository->findRandom(4);
        $heartStrokeProduct = $productRepository->findOneHeartStroke();
        $lastProducts = $productRepository->findLastProducts(4);
        $bestProducts = $productRepository->findBestProducts();

        return $this->render('index/home.html.twig', [
            'random_products' => $randomProducts,
            'heart_stroke_product' => $heartStrokeProduct,
            'last_products' => $lastProducts,
            'best_products' => $bestProducts,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('index/contact.html.twig');
    }
}
