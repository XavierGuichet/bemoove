<?php

namespace Bemoove\AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Bemoove\AppBundle\Form\UserType;
use Bemoove\AppBundle\Form\Sporty\ProfileType;

class FullRegistrationType extends UserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('profile',ProfileType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bemoove\AppBundle\Entity\User',
        ));
    }
}
