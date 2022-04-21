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

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($File) {
                $originalFilename = pathinfo($File->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$File->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $File->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'Filename' property to store the PDF file name
                // instead of its contents
                $image->setFilename($newFilename);
            }

            // ... persist the $image variable or any other work
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
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {

            $dossier_nom = $form->get('nom')->getData();
            mkdir("./photos/".$dossier_nom, 0777);
            dump($dossier_nom);
            return $this->redirectToRoute('app_home');
        }
        
        
        return $this->renderForm('upload/detail.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
        
    }
}
