<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Upload {

    private $dir;
    private $slugger;

    public function __construct(string $dir, SluggerInterface $slugger){
        $this->dir = $dir;
        $this->slugger = $slugger;
    }
    
    function handle(UploadedFile $file){
        $originalFilename = $file->getClientOriginalName();
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->dir,$fileName);
        return $fileName;
    }
}