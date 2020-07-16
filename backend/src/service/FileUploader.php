<?php 
namespace App\service;
use League\Flysystem\FilesystemInterface; 

use Gedmo\Sluggable\Util\Urlizer; 
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader  { 
        private $fileSystem ; 
        private $directory = 'image/'; 


        public function __construct(FilesystemInterface $publicUploadsFilesystem)
        {
            $this->fileSystem =  $publicUploadsFilesystem; 
        }

        public function uploadVehicleImage(File $file){ 
           
          if($file instanceof UploadedFile) {
            $filename= $file->getClientOriginalName(); 
            }else { $filename = $file->getFilename();}
             $url=Urlizer::urlize( pathinfo($filename, PATHINFO_FILENAME))
               .'-'.uniqid().'.'.$file->guessExtension();
                $this->fileSystem->write(
                  $this->directory.$url, file_get_contents($file->getPathname())
               ); 
            
               return $url; 

          }
          

         public function readStream($url ){ 
      
            return $this->fileSystem->readStream($url); 
         }
         
         public function deleteFolder(){ 
            $this->fileSystem->deleteDir($this->directory);
         }
         public function deleteImage($image){ 
              $this->fileSystem->delete($this->directory.$image); 
         }
         }
    
   

?> 