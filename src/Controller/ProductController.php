<?php

/*
 * This file is part of the ecommerce-symfony package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_list")
     */
    public function list(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        return $this->render('product/list.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'current_category' => null,
            'last_product' => $productRepository->findLastProducts(1),
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        $sum = 0;

        foreach ($product->getReviews() as $review) {
            $sum += $review->getNote();
        }

        $average = $sum / $product->getReviews()->count();

        $review = new Review();
        $reviewForm = $this->createForm(ReviewType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $review->setCreatedAt(new \DateTime());
            $review->setProduct($product);

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'average' => floor($average * 2) / 2,
            'review_form' => $reviewForm->createView(),
        ]);
    }
}
