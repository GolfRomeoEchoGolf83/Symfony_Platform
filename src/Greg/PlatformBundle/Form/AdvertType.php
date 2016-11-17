<?php
/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 16/11/2016
 * Time: 17:19
 */
namespace Greg\PlatformBundle\Form;

use Symfony\Bridge\Doctrine\Form\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',       DateTimeType::class)
            ->add('title',      TextType::class)
            ->add('author',     TextType::class)
            ->add('content',    TextareaType::class)
            ->add('image',      ImageType::class)
            ->add('categories', CollectionType::class, array(
                'entry_type'    => CategoryType::class,
                'allow_add'     => true,
                'allow_delete'  => true
            ))
            ->add('save',      SubmitType::class);

        // fonction d'écoute sur un évènement
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, // arg1 : évènement qui nous intéresse PRE_SET_DATA
            function (FormEvent $event) { // arg2 : quand l'évènement est déclenché
                // récupère l'advert Advert
                $advert = $event->getData();
                if (null === $advert) {
                    return;
                }
                // si advert non publiée ou n'existe pas
                if (!$advert->getPublished() || null === $advert->getId()) {
                    // ajout du champ published
                    $event
                        ->getForm()
                        ->add('published', CheckboxType::class, array('required' => false));
                } else { // sinon le supprime
                    $event->getForm()->remove('published');
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data-class'    => 'Greg\PlatformBundle\Entity\Advert'
        ));
    }
}