<?php

namespace Bemoove\AppBundle\Form\Type\Sporty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Bemoove\AppBundle\Form\Type\Place\AddressType;
use Bemoove\AppBundle\Form\Type\ImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('address', AddressType::class)
            ->add('telHome', TextType::class)
            ->add('telMobile', TextType::class)
            ->add('ismale', ChoiceType::class, array(
                                'choices'  => array(
                                    'Male' => true,
                                    'Female' => false,
                                )
                            ))
            ->add('birthday', DateType::class, array(
                                    'input'  => 'datetime',
                                    'widget' => 'choice',
                                ))
            ->add('presentation',TextareaType::class)
            ->add('avatar',ImageType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        'data_class' => 'Bemoove\AppBundle\Entity\Profile',
        ));
    }
}
