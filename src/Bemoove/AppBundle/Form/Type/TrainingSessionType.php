<?php

namespace Bemoove\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Bemoove\AppBundle\Form\Type\Place\AddressType;
use Bemoove\AppBundle\Form\Type\ImageType;

class TrainingSessionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('description',TextareaType::class)
            ->add('date',DateTimeType::class)
            ->add('sport', EntityType::class, array(
                'class' => 'BemooveAppBundle:Sport',
                'choice_label' => 'name',
            ))
            ->add('tags', CollectionType::class, array(
                'entry_type'   => TagType::class,
                'allow_add'    => true,
                'allow_delete' => true,
            ))
            ->add('duration',TimeType::class)
            ->add('address',AddressType::class)
            ->add('nbTicketAvailable',IntegerType::class)
            ->add('price',MoneyType::class)
            ->add('photo',ImageType::class)
            ;
    }
}
