<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
            ->add('isDone', RadioType::class,
                [
                    'required' => false,
                    'label' => 'Tâche terminée',
                ]
            )
            //->add('author') ===> must be the user authenticated
        ;
    }
}
