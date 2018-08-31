<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                array(
                    'label' => 'Nom d\'utilisateur',
                    'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'Nom d\'utilisateur',
                        'class' => 'form-control'
                    )
                )
            )
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Mot de passe',
                    'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'Mot de passe',
                        'class' => 'form-control'
                    )
                ),
                'second_options' => array(
                    'label' => 'Répéter le mot de passe',
                    'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'Répéter le mot de passe',
                        'class' => 'form-control'
                    )
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Utilisateur::class,
        ));
    }
}