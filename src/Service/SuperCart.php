<?php

/*
 * This file is part of the ecommerce-symfony package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SuperCart
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add(Product $product, $qty)
    {
        $products = $this->session->get('products', []);
        $isNew = true;

        // On vérifie si le produit est déjà dans le panier
        foreach ($products as &$cartItem) {
            if ($cartItem['product']->getId() === $product->getId()) {
                $cartItem['quantity'] += $qty;
                $isNew = false; // Je mets à jour un produit existant
            }
        }
        // Si le produit n'est pas déjà dans le panier
        if ($isNew) {
            $products[] = [
                'quantity' => $qty,
                'product' => $product,
            ];
        }

        $this->session->set('products', $products);
    }

    /**
     * Récupèrer tous les produits du panier.
     */
    public function getProducts()
    {
        $products = $this->session->get('products', []);

        return $products;
    }

    public function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->getProducts() as $cartItem) {
            $subtotal += $cartItem['product']->getPrice() / 100 * $cartItem['quantity'];
        }

        return $subtotal;
    }

    public function getTotal()
    {
        return $this->getSubtotal() + 6.90;
    }

    /**
     * Compter le nombre de produits dans le panier.
     */
    public function count()
    {
        $products = $this->session->get('products', []);
        $count = 0;

        foreach ($products as $cartItem) {
            $count += 1 * $cartItem['quantity'];
        }

        return $count;
    }
}
