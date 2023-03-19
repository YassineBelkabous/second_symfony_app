<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\FormHandler\CategoryFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    
    private $entityManager;
    private $handle;
    
    public function __construct(EntityManagerInterface $entityManager , CategoryFormHandler $cat)
    {
        $this->entityManager = $entityManager;
        $this->handle = $cat;
    }

    #[Route('/category', name: 'app_category')]
    public function add(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);

        if ($this->handle->handle($form,$request)) {

            return $this->redirectToRoute('list_cat');
        }
        return $this->render('category/index.html.twig', [
            'form' =>  $form->createView(),
        ]);
    }

    #[Route('/list_cat', name: 'list_cat')]
    public function list(EntityManagerInterface $entityManager): Response
    {

        $repository = $entityManager->getRepository(Category::class);
        $products = $repository->findAll();
        
        return $this->render('category/list.html.twig',['products'=>$products]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(int $id)
    {
        $this->handle->remove($id);
        return $this->redirectToRoute('list_cat');
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Category $cat , Request $request)
    {
        $form = $this->createForm(CategoryType::class,$cat); 
        if($this->handle->edit($request,$form,$cat)){
          return  $this->redirectToRoute('list_cat');
        }
        return $this->render('category/edit.html.twig',['form'=>$form->createView()]);
    }
}
