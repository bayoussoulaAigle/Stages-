<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;  
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

//Inclure les entité dans le controleur 
use App\Entity\Station;
use App\Repository\StationRepository;
use App\Repository\UserRepository;
use App\Form\StationType;
use App\Entity\Commentaire;
use App\Form\CommentType;
use App\Entity\User;


// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;



class StageController extends AbstractController
{ 
    /**
     * @Route("/stage", name="stage")
     */
    public function index(StationRepository $repo)
    {   
        $user= $this->getUser();
        
        if($user->getFonction()==='entreprise'){
            $stations =$repo->findBy(array('nomEntreprise' => $user->getUsername()));
            $ref=1;
            $title = 'supprimer';
        }

        if ($user->getFonction()==='professeur') {
            $stations = $repo->findBy(array('public' => '0'));
            $ref=2;
            $title = 'rendre accessible aux etudiants ';
        }

        if($user->getFonction()==='etudiant'){
            $stations = $repo->findBy(array('public' => 'true', 'filiere' => $user->getSecteurActiviter()));
            $ref=0;
            $title='telecharger';
        }

        return $this->render('stage/index.html.twig', [
            'controller_name' => 'StageController',
            'stations' => $stations,
            'ref' => $ref,
            'title' => $title,
            
        ]);
    }

    /**
     * @Route("/", name="home")
    */
    public function home(){
        $user= $this->getUser();
        if($user === null){
            return $this->render('stage/home.html.twig');
        }
        if($user->getFonction()==='entreprise'){
           return $this->redirectToRoute('stage_entreprise');
        }
        if($user->getFonction()==='professeur'){
            return $this->redirectToRoute('stage_professeur');
        }
        if($user->getFonction()==='etudiant'){
            return $this->redirectToRoute('stage_etudiant');
        }
                  
    }


    /**
     * @Route("/stage/{id}", name="stage_show")
     */
    public function show(Station $station, Request $request, ObjectManager $manager){
        $user=$this->getUser();
        if($user->getFonction()==='entreprise'){ $ref = 1;}
        if($user->getFonction()==='etudiant'){ $ref = 0;}
        if($user->getFonction()==='professeur'){ $ref = 2;}
        $commentaire = new Commentaire();
        
        $form = $this->createForm(CommentType::class, $commentaire);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentaire->setDateCommentaire(new \DateTime())
                        ->setStation($station)
                        ->setAuteur($user->getUsername());
            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('stage_show', ['id' =>
            $station->getId()]);
        }

        return $this->render('stage/show.html.twig', [
            'station' => $station,
            'commentForm' => $form->createView(),
            'ref' => $ref
        ]);
    }

    
    /**
     * @Route("/new", name="stage_creer")
     * @Route("/stage/{id}/modifier", name="stage_modifier")
     */
    public function creer(Station $station=null, Request $request, ObjectManager $manager){
        $user=$this->getUser();
       if (!$station) {
           $station = new Station();
       }
    
        $form = $this->createForm(StationType::class, $station);

        $form->handleRequest($request);
        if ($user != null) {
            if ($user->getFonction()==='professeur' && $form->isSubmitted() && $form->isValid()) {
                $station->setDate(new \DateTime());
                $station->setPublic(true);
            
                $manager-> persist($station);
                $manager-> flush();
            }
        }

        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            if (!$station->getId()) {
                $station->setNomEntreprise($user->getUsername());
                $station->setDate(new \DateTime());
                $station->setPublic(false);
                $file = $station->getFichier();
                if($file!= null ){
                    $station_directory = $this->getParameter('station_directory');
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move(
                    $station_directory,
                    $fileName);
                    $station->setFichier($fileName);
                }
            }

           $em-> persist($station);
           $em-> flush();
         
       return $this->redirectToRoute('stage_show', ['id'=> $station->getId()]);
        }

         return $this->render('stage/creer.html.twig', [
             'formStation' => $form->createView(),
             'modifierMode' => $station->getId() !== null
         ]);

    }

    /**
     * @Route("/stage/new/etudiant/entreprise/professeur",name="stage_professeur")
     * @Security("has_role('ROLE_USER')")
     */
    public function professeur(){
        return $this->render('stage/professeur.html.twig');
    }
    

     /**
     * @Route("/stage/new/etudiant", name="stage_etudiant")
     * @Security("has_role('ROLE_USER')")
     */
    public function etudiant(){
        return $this->render('stage/etudiant.html.twig');
    }

    /**
     * @Route("/stage/new/etudiant/entreprise", name="stage_entreprise")
     * @Security("has_role('ROLE_USER')")
     */
    public function entreprise(){
        return $this->render('stage/entreprise.html.twig');
    }


    /**
     * @Route("/",name="user_info")
     * @Security("is_granted('IS_AUTHENTIFICATED_FULLY')")
     */
    public function contolerAcces(){
        if($this->get('security.authorization_checker')->$isGaranted('Role_ADMIN')){
            return $ths->render('stage/home.html.twig');
        }
        if ($this->get('security.authorization_checker')->$isGaranted('Role_USER')) {
            return $ths->render('stage/entreprise.html.twig');
        }

    }

    /**
     *@Route("/stage/entreprise/delete{id}", name = "stage_supprimer") 
     */
    public function supprimer(Station $station){
        $em = $this->getDoctrine()->getManager();
        $em->remove ( $station );
        $em-> flush ();
        return $this->redirectToRoute('stage');
    }

    /**
     *@Route("/stage/professeur/public {id}", name = "stage_public") 
     */
    public function publicStage(Station $station){
        $em = $this->getDoctrine()->getManager();
        $station->setPublic('true');
        $em->persist($station);
        $em-> flush ();
        return $this->redirectToRoute('stage');
    }


     /**
    * @Route("/stage/entreprise/contact", name="contact_entreprise")
    */
    public function contactEntreprise(UserRepository $repo)
    {
        $stations = $repo->findBy(array('fonction' => 'entreprise'));
        $ref=1;
        $title = 'Liste d\'ntreprises';

        return $this->render('stage/contact.html.twig', [
            'stations' => $stations,
            'ref' => $ref,
            'title' => $title
        ]);
    
    }

     /**
    * @Route("/stage/etudiant/contact", name="contact_etudiant")
    */
    public function contactEtudiant(UserRepository $repo)
    {    
        $stations = $repo->findBy(array('fonction' => 'etudiant'));
        $ref=0;
        $title = 'Liste d\'étudiants';

        return $this->render('stage/contact.html.twig', [
            'stations' => $stations,
            'ref' => $ref,
            'title' => $title
        ]);
    
    }

     /**
    * @Route("/stage/professeur/contact", name="contact_professeur")
    */
    public function contactProfesseur(UserRepository $repo)
    { 
        $stations = $repo->findBy(array('fonction' => 'professeur'));
        $ref=2;
        $title = 'Liste d\'étudiants';

        return $this->render('stage/contact.html.twig', [
            'stations' => $stations,
            'ref' => $ref,
            'title' => $title
        ]);
    
    }

    /**
     * @Route("/stage/professeur/public", name="stage_valider_prof")
     * @Security("has_role('ROLE_USER')")
     */
    public function stageValiderProf(StationRepository $repo){
        $stations = $repo->findBy(array('public' => 'true'));

        return $this->render('stage/stageValiderProf.html.twig', [
            'controller_name' => 'StageController',
            'stations' => $stations,
            'title' => 'Télécharger',
            
        ]);

    }

     /**
     * @Route("/",name="user_info")
     * @Security("is_granted('IS_AUTHENTIFICATED_FULLY')")
     */
    public function indexAction ( Security $security ){
        $user = $security -> getUser();

        if ($user->getFonction() == "entreprise") {
            return $this->redirectToRoute('stage_entreprise');
        }
        if ($user->getFonction() == "etudiant") {
            return $this->redirectToRoute('stage_etudiant');
        }
        if ($user->getFonction == "professeur") {
            return $this->render('stage/entreprise.html.twig');
        }
    }
        /*
        /**
         * Undocumented function
         *
         * @return string
         *
        private function generateUniqueFileName(){
            return md5(uniqid());
        }

    
    public function respond($filename){
        $reponse = new Response(file_get_contents($this->fichier), 200 ,[
            'Content-Description' => 'File Transfer',
            'Content->Disposition' => 'attachemen; filename="' .$filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
        ]);
        unlink($this->pdf);
        $response->send();
    }*/


    public function fichierpdf(){

    //Telechargement du fichier pdf 
    // Include Dompdf required namespaces use Dompdf\Dompdf; use Dompdf\Options;
    // Configure Dompdf according to your needs 

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial'); 
    // Instantiate Dompdf with our options 
    $dompdf = new Dompdf($pdfOptions); 
    // Retrieve the HTML generated in our twig file
         $html = $this->renderView('default/mypdf.html.twig',
        [ 'title' => "Welcome to our PDF Test" ]); 
        // Load HTML to Dompdf 
        $dompdf->loadHtml($html); 
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait' 
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF 
        $dompdf->render();
        // Output the generated PDF to Browser (force download)
            $dompdf->stream("mypdf.pdf", [ "Attachment" => true ]); 
     }


    /**
     * @Route("/stage/pdf", name = "stage_pdf" )
     */
 
     //generer et afficher un fichier pdf dans le navigateur
     // { public function index() { 
    // Configure Dompdf according to your needs 
    public function afficherpdf(){
     $pdfOptions = new Options();
      $pdfOptions->set('defaultFont', 'Arial'); 
    // Instantiate Dompdf with our options 
    $dompdf = new Dompdf($pdfOptions); 
    // Retrieve the HTML generated in our twig file 
    $html = $this->renderView('satge/mypdf.html.twig',
     [ 'title' => "Welcome to our PDF Test" ]); 
     // Load HTML to Dompdf 
     $dompdf->loadHtml($html); 
     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait' 
     $dompdf->setPaper('A4', 'portrait'); 
     // Render the HTML as PDF 
     $dompdf->render(); 
     // Output the generated PDF to Browser (inline view)
      $dompdf->stream("mypdf.pdf", [ "Attachment" => false ]);
     } 



     //Generer et stocker un fichier pdf sur un disque 
     // public function index() { // Configure Dompdf according to your needs
        public function stocker(){
         $pdfOptions = new Options();
          $pdfOptions->set('defaultFont', 'Arial');
          // Instantiate Dompdf with our options 
          $dompdf = new Dompdf($pdfOptions); 
          // Retrieve the HTML generated in our twig file 
          $html = $this->renderView('default/mypdf.html.twig', [
               'title' => "Welcome to our PDF Test" ]); 
         // Load HTML to Dompdf 
         $dompdf->loadHtml($html); 
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
          $dompdf->setPaper('A4', 'portrait'); 
          // Render the HTML as PDF 
          $dompdf->render(); 
          // Store PDF Binary Data 
          $output = $dompdf->output(); 
          // In this case, we want to write the file in the public directory 
          $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
           // eg /var/www/project/public/mypdf.pdf 
           $pdfFilepath = $publicDirectory . '/mypdf.pdf'; 
           // Write file to the desired path 
           file_put_contents($pdfFilepath, $output); 
           // Send some text response 
           return new Response("The PDF file has been succesfully generated !");
         } 




   

 
    
}

