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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category_show")
     */
    public function show(Category $category, CategoryRepository $categoryRepository)
    {
        return $this->render('product/list.html.twig', [
            'products' => $category->getProducts(),
            'categories' => $categoryRepository->findAll(),
            'current_category' => $category,
        ]);
    }
}
