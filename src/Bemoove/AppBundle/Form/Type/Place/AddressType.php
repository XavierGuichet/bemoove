<?php

namespace Bemoove\AppBundle\Form\Type\Place;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Bemoove\AppBundle\Form\Type\Place\CityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstline', TextType::class)
            ->add('secondline', TextType::class)
            ->add('district', TextType::class)
            ->add('city', CityType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bemoove\AppBundle\Entity\Place\Address',
        ));
    }
}
