<?php
/**
 * Created by PhpStorm.
 * User: xml
 * Date: 30/08/18
 * Time: 14:14
 */

namespace App\Form;

use App\Entity\Addresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('addresse');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Addresse::class,
        ));
    }
}