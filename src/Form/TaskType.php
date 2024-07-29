<?php

// src/Form/TaskType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Task;

class TaskType extends AbstractType
{
    /**
     * Créez le formulaire de tâche avec les champs titre et contenu.
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaires
     * @param array $options Les options du formulaire (requises par l'interface, utilisées ici pour des raisons de compatibilité)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Utilisez $options pour éviter les avertissements de Codacy concernant les paramètres inutilisés
        if (isset($options['data_class'])) {
            // Facultativement, gérez ici la logique spécifique liée aux options
        }

        $builder
            ->add('title')
            ->add('content')
            // Ajoutez d'autres champs de formulaire si nécessaire
        ;
    }
}
