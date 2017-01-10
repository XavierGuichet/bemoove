<?php

namespace Bemoove\AppBundle\Form\Type;

use Bemoove\UserBundle\Form\BaseUserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends BaseUserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('Roles', ChoiceType::class, array(
            'choices'  => array(
                'Sporty' => "Sporty",
                'Coach' => "Coach"
            ),
            'mapped' => false,
        ));
    }
}
