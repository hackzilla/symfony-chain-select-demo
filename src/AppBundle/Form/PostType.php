<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use AppBundle\Form\ChoiceList\Level2ChoiceList;
use AppBundle\Form\ChoiceList\Level3ChoiceList;
use AppBundle\Form\Type\Level1Type;
use AppBundle\Form\Type\Level2Type;
use AppBundle\Form\Type\Level3Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and manipulate blog posts.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class PostType extends AbstractType
{
    private $level2ChoiceList;
    private $level3ChoiceList;

    public function __construct()
    {
        $this->level2ChoiceList = new Level2ChoiceList();
        $this->level3ChoiceList = new Level3ChoiceList();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // For the full reference of options defined by each form field type
        // see http://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        //
        //     $builder->add('title', null, array('required' => false, ...));

        $builder
            ->add(
                'title',
                null,
                [
                    'attr'  => ['autofocus' => true],
                    'label' => 'label.title',
                ]
            )
            ->add('summary', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', ['label' => 'label.summary'])
            ->add(
                'content',
                null,
                [
                    'attr'  => ['rows' => 20],
                    'label' => 'label.content',
                ]
            )
            ->add('authorEmail', null, ['label' => 'label.author_email'])
            ->add(
                'publishedAt',
                'AppBundle\Form\Type\DateTimePickerType',
                [
                    'label' => 'label.published_at',
                ]
            )
            ->add(
                'level1',
                Level1Type::class,
                [
                ]
            );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }


    protected function addElements(FormInterface $builder, $level1 = null, $level2 = null)
    {
        // Remove the submit button, we will place this at the end of the form later
//        $submit = $form->get('save');
//        $form->remove('save');

        if ($level1) {
            $builder->add('level2', Level2Type::class, [
                'level1'  => $level1,
                'choices' => $this->level2ChoiceList->getChoices($level1),
            ]);
        }

        if ($level2) {
            $builder->add('level3', Level3Type::class, [
                'level2'  => $level2,
                'choices' => $this->level3ChoiceList->getChoices($level2),
            ]);
        }

        // Add submit button again, this time, it's back at the end of the form
//        $form->add($submit);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->addElements(
            $form,
            $data['level1'],
            !empty($data['level2']) ? $data['level2'] : null
        );
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $this->addElements($form, $data->getLevel1(), $data->getLevel2());
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post',
        ));
    }
}
