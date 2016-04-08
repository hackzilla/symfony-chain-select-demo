<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Level1Type extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder'       => 'Choose a Level',
            'label'             => '1st level',
            'choices_as_values' => true,
            'choices'           => $this->getChoices(),
            'empty_data'        => '-1',
        ]);
    }

    private function getChoices()
    {
        $choices = [];

        for ($i=1; $i<6; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
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
