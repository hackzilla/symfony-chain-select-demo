<?php

namespace AppBundle\Form\ChoiceList;

class Level2ChoiceList
{
    public function getChoices($level1)
    {
        $choices = [];

        for ($i=1; $i<6; $i++) {
            $j = $i * $level1;
            $choices[$j] = $j;
        }

        return $choices;
    }
}
