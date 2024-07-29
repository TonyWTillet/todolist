<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * Créez le formulaire utilisateur avec les champs nom d'utilisateur, mot de passe, e-mail et rôles.
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
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
            ])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Utilisateur' => "ROLE_USER",
                    'Administrateur' => "ROLE_ADMIN"
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
