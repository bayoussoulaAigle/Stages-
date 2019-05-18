<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Station;
use App\Entity\Commentaire;
use App\Entity\User;


class StationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
          //Creer 4  a 12 Edition du stage 
        for ($i=1; $i <= mt_rand(4, 12); $i++) {
            $station = new Station();

            $message = "je cherche les perfermants etudiants ";
          
            if ($i%2 == 0) {
                $station->setNomEntreprise("Total")
                   ->setFiliere("Miage")
                   ->setNiveauEtude("M1")
                   ->setPublic(false)
                   ->setNombreEtudiant(12)
                   ->setDateDebutStage(new \DateTime())
                   ->setMessage($message)
                   ->setDate(new \DateTime());
            } elseif ($i%3 ==0) {
                $station->setNomEntreprise("Eni")
                   ->setFiliere("Miage")
                   ->setNiveauEtude("M1")
                   ->setPublic(false)
                   ->setNombreEtudiant(12)
                   ->setDateDebutStage(new \DateTime())
                   ->setMessage($message)
                   ->setDate(new \DateTime());
            } else if ($i%5 == 0) {
                $station->setNomEntreprise("Lokossa")
                        ->setFiliere("Miage")
                        ->setNiveauEtude("M1")
                        ->setPublic(false)
                        ->setNombreEtudiant(8)
                        ->setDateDebutStage(new \DateTime())
                        ->setMessage($message)
                        ->setDate(new \DateTime());
            }else{
                $station->setNomEntreprise("Renco")
                    ->setFiliere("Inge")
                    ->setNiveauEtude("M2")
                    ->setPublic(false)
                    ->setNombreEtudiant(25)
                    ->setDateDebutStage(new \DateTime())
                    ->setMessage($message)
                    ->setDate(new \DateTime());
            }
            
            $manager->persist($station);

           
             for ($j=1; $j <= mt_rand(4, 10); $j++) {
                $comment = new Commentaire();
                $contenu = "J'ai aimer cette entreprise";
   
                $days = (new \DateTime())->diff($station->getDate())->days;
        
                $comment->setAuteur("Sophie")
                   ->setContenu($contenu)
                   ->setDateCommentaire(new \DateTime())
                   ->setStation($station);

                $manager->persist($comment);
               }
            }
        
    
        $manager->flush();
    }
    
}
