<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;

use App\Form\ProgramSearchType;
use App\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request) :Response
    {
        $message = '';

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $message = "La catégorie a été ajoutée avec succès.";
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories found in category\'s table.'
            );
        }

        return $this->render('wild/categories.html.twig', [
                'categories' => $categories,
                'message' => $message,
                'form' => $form->createView(),
            ]
        );
    }
}

