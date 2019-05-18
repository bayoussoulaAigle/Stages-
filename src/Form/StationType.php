<?php

namespace App\Form;

use App\Entity\Station;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType ;


class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ( 'filiere' , ChoiceType :: class , [
                     'choices' => [
                     'Toutes Filieres' => 'tout',
                     'Ingenieurie' => 'ingenieurie' ,
                     'Miage' => 'miage' ,
                     'Simsa' => 'simsa' ,
                     'Sir' => 'sir' ,
                     'Robotique' => 'robotique' ,
                          ],
                     'choice_attr' => function ( $choice , $key , $value ) {
                 // adds a class like attending_yes, attending_no, etc
                 return [ 'class' => 'attending_' . strtolower ( $key )];},])

            ->add('titre')
            -> add ( 'niveauEtude' , ChoiceType :: class , [
                'choices' => [
                'Licence 2' => 'L2',
                'Licence 3' => 'L3' ,
                'Master 1' => 'M1' ,
                'Master 2 ' => 'M2' ,
                     ],
                'choice_attr' => function ( $choice , $key , $value ) {
            // adds a class like attending_yes, attending_no, etc
            return [ 'class' => 'attending_' . strtolower ( $key )];},])
            ->add('nombreEtudiant')
            ->add('fichier',  FileType::class, ['label' => 'Fichier (PDF file)',
            'data_class' => null,'required' => false])
            ->add('message')
            ->add('dateDebutStage')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Station::class,
        ]);
    }
}


