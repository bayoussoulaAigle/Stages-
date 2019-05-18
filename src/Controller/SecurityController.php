<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use App\Form\RegistationType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager,
    UserPasswordEncoderInterface $encoder){
        $user = new User();

       $form = $this->createForm(RegistationType::class, $user);

       $form->handleRequest($request);
      
       if($form->isSubmitted() && $form->isValid()) {
           $hash = $encoder->encodePassword($user, $user->getPassword());
           $user->setPassword($hash);
           $manager->persist($user);
           $manager->flush();

           return $this->redirectToRoute('app_login');
       }
       
       return $this->render('security/registration.html.twig', [
           'form' => $form->createView()
       ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @Route("/connexion", name="security_login")
     */
  //      return $this->render('security/login.html.twig');
  //  }


    /**
     * @Route("/deconnexion", name="security_logout")
     */
  
     public function logout(){
      
    }

    /**
     * @Route("/login", name="app_login")
     */
  
   
    /**
     * @Route("/form/delete/{id<\d+>}", methods={"POST"})
     */
  /*  public function delete(Request $request, Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($article);
        $em->flush();
    
        // redirige la page
        return $this->redirectToRoute('admin_article_index');
    }*/

 
    /**
     *@Route("/security/choixFormulaire", name = "security_choixFormulaire")
     */
    
    public function choixFormulaire(){
        return $this->render('security/choixFormulaire.html.twig');
    }

    /**
     *@Route("/security/ChoixFormulaire/formEntreprise", name="security_formEntreprise")
     */
    public function registrationEnreprise(Request $request, ObjectManager $manager,
        UserPasswordEncoderInterface $encoder){
            $title='Entreprise';
            $user = new User();
    
           $form = $this->createForm(RegistationType::class, $user);
    
           $form->handleRequest($request);
          
           if($form->isSubmitted() && $form->isValid()) {
               $hash = $encoder->encodePassword($user, $user->getPassword());
               $user->setPassword($hash);
               $manager->persist($user);
               $manager->flush();
    
               return $this->redirectToRoute('app_login');
           }
           
           return $this->render('security/registrationEntreprise.html.twig', [
               'form' => $form->createView(),
               'title' => $title,
           ]);

    }

    /**
     *@Route("/security/ChoixFormulaire/formEtudiant", name= "security_formEtudiant")
     */
    public function registrationEtudiant(Request $request, ObjectManager $manager,
        UserPasswordEncoderInterface $encoder){
            $title='Etudiant';
            $ref=0;
            $user = new User();
    
           $form = $this->createForm(RegistationType::class, $user);
    
           $form->handleRequest($request);
          
           if($form->isSubmitted() && $form->isValid()) {
               $hash = $encoder->encodePassword($user, $user->getPassword());
               $user->setPassword($hash);
               $manager->persist($user);
               $manager->flush();
    
               return $this->redirectToRoute('app_login');
           }
           
           return $this->render('security/registrationEtudiant.html.twig', [
               'form' => $form->createView(),
               'title' => $title,
           ]);

    }

    
    /**
     *@Route("/security/choixFormulaire/formProfesseur", name = "security_formProfesseur")
     */
    public function registrationProfesseur(Request $request, ObjectManager $manager,
        UserPasswordEncoderInterface $encoder){
            $title='Professeur';
            $ref=2;
            $user = new User();
    
           $form = $this->createForm(RegistationType::class, $user);
    
           $form->handleRequest($request);
          
           if($form->isSubmitted() && $form->isValid()) {
               $hash = $encoder->encodePassword($user, $user->getPassword());
               $user->setPassword($hash);
               $manager->persist($user);
               $manager->flush();
    
               return $this->redirectToRoute('app_login');
           }
           
           return $this->render('security/registrationProfesseur.html.twig', [
               'form' => $form->createView(),
               'title' => $title,
           ]);

    }



  
   
}
