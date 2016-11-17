<?php
/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 16/11/2016
 * Time: 17:19
 */
namespace Greg\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvertEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('date');
    }

    public function getParent()
    {
        return AdvertType::class;
    }
}