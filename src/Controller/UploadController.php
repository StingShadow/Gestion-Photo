<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Form\ImageType;
use App\Entity\Image;
use App\Form\DossierType;
use App\Repository\DossierRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\String\Slugger\SluggerInterface;


class UploadController extends AbstractController
{
   /**
     * @Route("/Upload", name="app_upload")
     */
    public function new(Request $request, SluggerInterface $slugger, ImageRepository $imageRepository)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         
            $File = $form->get('filename')->getData();

         
            if ($File) {
                $originalFilename = pathinfo($File->getClientOriginalName(), PATHINFO_FILENAME);
       
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'.'.$File->guessExtension();

              
                try {
                    $File->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
     
                }

           
                $image->setFilename($newFilename);
            }

            $image->setValidation(false);

            $imageRepository->add($image);

            return $this->redirectToRoute('app_upload_detail', ['id' => $image->getId()]);
        }

        return $this->renderForm('upload/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/Upload/detail", name="app_upload_detail")
     */
    public function detail(Request $request, ImageRepository $imageRepository, DossierRepository $dossierRepository)
    {
        
        $image = new Image();
        $image = $imageRepository->findOneById($request->get('id'));
        $filepath = "photos/".$image->getFilename();
        $nom_image = explode(".", $image->getFilename());
        $nom_image = $nom_image[0];
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {

            $dossier_nom = $form->get('nom')->getData();
            if ($dossierRepository->findOneByNom($dossier_nom)){
                $destinationPath = "photos/".$dossier_nom."/".$image->getFilename(); 
                rename($filepath,$destinationPath);
            } else {
                mkdir("./photos/".$dossier_nom, 0777);
                $destinationPath = "photos/".$dossier_nom."/".$image->getFilename(); 
                rename($filepath,$destinationPath);
                $dossier->setNom($dossier_nom);
                $dossierRepository->add($dossier);
            }
            
            return $this->redirectToRoute('app_home');
        }
        
        
        return $this->renderForm('upload/detail.html.twig', [
            'image' => $image,
            'form' => $form,
            'nom' => $nom_image
        ]);
        
    }
}
