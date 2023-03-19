<?php
namespace App\Form\FormHandler;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFormHandler 
{

    private $entitymanager;
    private $slugger;

    function __construct(EntityManagerInterface $e, SluggerInterface $s)
    {
        $this->entitymanager = $e;
        $this->slugger = $s;
    }


        public function upload(UploadedFile $file)
        {
            $originalFilename = $file->getClientOriginalName();
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            return $fileName;
        }

    public function handle(FormInterface $form , Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $category = $form->getData();
            $image = $form->get('image')->getData();
            if($image)
            {
                $category->setImage($this->upload($image));
            }

            $this->entitymanager->persist($category);    
            $this->entitymanager->flush();

            return true;
         }
        
         return false;

    }

    public function remove($id)
    {
        $repository = $this->entitymanager->getRepository(Category::class);
        $category = $repository->find($id);
        $this->entitymanager->remove($category);
        $this->entitymanager->flush();
    }

    public function edit(Request $request,FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $cat->setName($form->get('name')->getData());
            // $cat->setDescription($form->get('description')->getData());
            $cat = $form->getData();
            $this->entitymanager->persist($cat);
            $this->entitymanager->flush($cat);

            return true;
        }
        return false;
    }

}