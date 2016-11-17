<?php
/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 17/11/2016
 * Time: 15:04
 */

namespace Greg\PlatformBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Greg/PlatformBundle\Entity\Category'
        ));
    }
}