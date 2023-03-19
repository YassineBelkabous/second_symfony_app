<?php
namespace App\Form\FormHandler;

use App\Service\Calcul;
use App\Service\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;


    class ProductFormHandler
    {
        private $entitymanager;
        private $cal;
        private $upload;


        public function __construct(EntityManagerInterface $entitymanager, Calcul $cal, Upload $upload){
            $this->entitymanager = $entitymanager;
            $this->cal = $cal;
            $this->upload  = $upload;
            }

        function handle(FormInterface $form , Request $request) {
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            
                $product = $form->getData();
                $image = $form->get('image')->getData();

                if($image)
                {
                    $product->setImage($this->upload->handle($image));
                }
    
                if($form->get('ttc')->getData()){
    
                    $price = $form->get('price')->getData();
                    $price = $this->cal->calculPrixTTC($price);
                    
                    $product->setPrice($price);
                }
    
                $this->entitymanager->persist($product);    
                $this->entitymanager->flush();

                return true;
             }
            
             return false;

             
    }

}