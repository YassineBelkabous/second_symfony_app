<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\FormHandler\ProductFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $entityManager;
    private $handle;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,ProductFormHandler $handle)
    {
        $this->entityManager = $entityManager;
        $this->handle = $handle;

    }

    #[Route('/product', name: 'app_product')]
    public function index(Request $request): Response
    {

        // $product = new Product();
        // $form = $this->createFormBuilder($product)
        // ->add('name', TextType::class)
        // ->add('price', NumberType::class)
        // ->add('quantity', NumberType::class)
        // ->add('description', TextType::class)
        // ->add('add',SubmitType::class ,['label'=>'Add product'])
        // ->getForm();

        $form = $this->createForm(ProductType::class);

        $dir = $this->getParameter('kernel.project_dir').'/public/images';

        if ($this->handle->handle($form,$request)) {

            return $this->redirectToRoute('list');
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(EntityManagerInterface $entityManager): Response
    {

        $repository = $entityManager->getRepository(Product::class);
        $products = $repository->findAll();
        
        return $this->render('product/list.html.twig',['products'=>$products]);
    }
}
