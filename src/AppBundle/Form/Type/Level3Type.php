<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Level3Type extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder'       => 'Choose a 3rd Level',
            'label'             => '3rd Level',
            'choices_as_values' => true,
            'choices'           => [],
            'empty_data'        => '-1',
            'level2'            => false,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'level1';
    }
}
