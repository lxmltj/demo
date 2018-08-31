<?php
/**
 * Created by PhpStorm.
 * User: xml
 * Date: 30/08/18
 * Time: 14:36
 */

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, array(
            'label_attr' => array('class' => 'control-label'),
            'attr' => array(
                'class' => 'form-control'
            )
        ));
        $builder->add('prenom', TextType::class, array(
            'label_attr' => array('class' => 'control-label'),
            'attr' => array(
                'class' => 'form-control'
            )
        ));
        $builder->add('email', TextType::class, array(
            'label_attr' => array('class' => 'control-label'),
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('addresses', CollectionType::class, array(
            'entry_type' => AddresseType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => false,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Contact::class,
        ));
    }
}