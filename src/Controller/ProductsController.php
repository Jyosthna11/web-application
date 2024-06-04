<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'All_products', methods: ['GET', 'POST'])]
    public function index(ProductsRepository $productsRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();

        $selectedCategoryId = $request->query->get('category');
        if ($selectedCategoryId) {
            $selectedCategory = $categoryRepository->find($selectedCategoryId);
            $products = $selectedCategory ? $selectedCategory->getProducts() : [];
        } else {
            $products = $productsRepository->findAll();
        }

        return $this->render('products/list.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
        ]);
    }
    #[Route('/delete/{id}', name: 'delete_product', requirements: ['id' => '\d+'])]
    public function delete(int $id, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): Response
    {
        $products = $productsRepository->find($id);

        if (!$products) {
            // Handle the case where the product with the given id is not found
            throw $this->createNotFoundException('Product not found');
        }

        // Get the associated category
        $category = $products->getCategory();

        // Disassociate the product from its category
        if ($category) {
            $category->removeProduct($products);
        }

        // Remove and flush
        $entityManager->remove($products);
        $entityManager->flush();

        return $this->redirectToRoute('All_products');
    }


    #[Route('/update/{id}', name: 'update_product', requirements: ['id' => '\d+'])]
    public function update(Request $request,int $id, ProductsRepository $productsRepository,EntityManagerInterface $entityManager): Response
    {
        try{
            $products = $productsRepository->findOneBy(["id" => $id]);


            if ($products === null) {
                throw $this->createNotFoundException('Product not found');
            }

            // Check if the user is not logged in
            if (!$this->getUser()) {
                // Redirect to the login page or handle the case where the user is not logged in
                return $this->redirectToRoute('login'); // Assuming your login route is named 'app_login'
            }

            // Check if the currently logged-in user matches the creator's ID of the product
            $loggedInUser = $this->getUser();

            if ($products->getCreator() !== $loggedInUser) {
                throw $this->createAccessDeniedException('You are not allowed to edit this product.');
            }


            $form = $this->createForm(ProductFormType::class, $products, [
                'label' => $id ? 'Update Product' : 'Create Product',
            ]);
            $form->handleRequest($request);
            $imagePath = $form->get('imagePath')->getData();




            if ($form->isSubmitted() && $form->isValid()) {
                $products->setName($form->get('name')->getData());
                $products->setDescription($form->get('description')->getData());
                $products->setPrice($form->get('price')->getData());

                //setting the image path
                $imagePath = $form->get('imagePath')->getData();
                if ($imagePath) {
                    $fileName = md5(uniqid()).'.'.$imagePath->guessExtension();
                    $imagePath->move($this->getParameter('uploads_directory'), $fileName);
                    $products->setImagepath($fileName);
                }

                $entityManager->persist($products);
                $entityManager->flush();
                $this->addFlash('success', 'updated successfully');

                return $this->redirectToRoute('All_products');
            }
            $productForm = $form->createView();
            return $this->render('products/update.html.twig', [
                'productForm' => $productForm,'product' => $products
            ]);
        }catch (AccessDeniedException $e) {
            // Handle the access denied exception, you can render a custom error page or redirect
            return $this->render('error/access_denied.html.twig', [
                'error' => $e->getMessage(),
            ]);
        }


    }

    #[Route('/products/add', name: 'add_products')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = new Products();

        $form = $this->createForm(ProductFormType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products->setName($form->get('name')->getData());
            $products->setDescription($form->get('description')->getData());
            $products->setPrice($form->get('price')->getData());

            //setting the image path
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $fileName = md5(uniqid()).'.'.$imagePath->guessExtension();
                $imagePath->move($this->getParameter('uploads_directory'), $fileName);
                $products->setImagepath($fileName);
            }
            $products->setCreator($this->getUser());


            $entityManager->persist($products);
            $entityManager->flush();

            return $this->redirectToRoute('All_products');
        }
        return $this->render('products/index.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    #[Route('/products/{id}', name: 'view_product', requirements: ['id' => '\d+'])]
    public function view(Products $products): Response
    {
        return $this->render('products/details.html.twig', [
            'product' => $products,
        ]);
    }
}
