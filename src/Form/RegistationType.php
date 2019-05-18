<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegistationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            -> add ( 'fonction' , ChoiceType :: class , [
                'choices' => [
                'Entreprise' => 'entreprise',
                'Professeur' => 'professeur' ,
                'Etudiant' => 'etudiant' ,
                     ],
                'choice_attr' => function ( $choice , $key , $value ) {
            // adds a class like attending_yes, attending_no, etc
            return [ 'class' => 'attending_' . strtolower ( $key )];},])
            ->add('numero')
            ->add('adresse')
            ->add('bPostale')
            ->add('ville')
            ->add('site')
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('secteurActiviter')
            ->add('telephone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
