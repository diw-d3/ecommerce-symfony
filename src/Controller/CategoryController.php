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

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}/{page}", name="category_show")
     */
    public function show($page = 1, Category $category, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $products = $productRepository->fingPaginator($page, $category);
        $maxPage = ceil(count($products) / 3);

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
            'current_category' => $category,
            'last_product' => $productRepository->findLastProducts(1, $category),
            'current_page' => $page,
            'max_page' => $maxPage,
        ]);
    }
}
