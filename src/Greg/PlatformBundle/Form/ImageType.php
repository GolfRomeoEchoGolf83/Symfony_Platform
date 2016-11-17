<?php
/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 17/11/2016
 * Time: 14:38
 */

namespace Greg\PlatformBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Greg\PlatformBundel\Entity\Image'
        ));
    }

}