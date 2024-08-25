<?php

namespace App\Tests\Unit\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Form\UserType;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserTypeTest extends TypeTestCase
{
    /**
     * Test if the form is built with the correct fields and options.
     */
    public function testBuildForm(): void
    {
        $form = $this->factory->create(UserType::class);

        $this->assertTrue($form->has('username'));
        $this->assertEquals(TextType::class, $form->get('username')->getConfig()->getType()->getInnerType()::class);
        $this->assertEquals("Nom d'utilisateur", $form->get('username')->getConfig()->getOption('label'));

        $this->assertTrue($form->has('password'));
        $passwordField = $form->get('password');
        $this->assertEquals(RepeatedType::class, $passwordField->getConfig()->getType()->getInnerType()::class);
        $this->assertEquals(PasswordType::class, $passwordField->getConfig()->getOption('type'));
        $this->assertEquals('Les deux mots de passe doivent correspondre.', $passwordField->getConfig()->getOption('invalid_message'));
        $this->assertEquals('Mot de passe', $passwordField->getConfig()->getOption('first_options')['label']);
        $this->assertEquals('Tapez le mot de passe à nouveau', $passwordField->getConfig()->getOption('second_options')['label']);

        $this->assertTrue($form->has('email'));
        $this->assertEquals(EmailType::class, $form->get('email')->getConfig()->getType()->getInnerType()::class);
        $this->assertEquals('Adresse email', $form->get('email')->getConfig()->getOption('label'));

        $this->assertTrue($form->has('roles'));
        $rolesField = $form->get('roles');
        $this->assertEquals(ChoiceType::class, $rolesField->getConfig()->getType()->getInnerType()::class);
        $this->assertEquals('Rôles', $rolesField->getConfig()->getOption('label'));
        $this->assertTrue($rolesField->getConfig()->getOption('multiple'));
        $this->assertFalse($rolesField->getConfig()->getOption('expanded'));
    }

    /**
     * Test form submission and data binding.
     *
     * @throws Exception
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'email' => 'johndoe@example.com',
            'roles' => ['ROLE_USER'],
            'Task' => new Task(),
        ];

        $model = new User();

        $form = $this->factory->create(UserType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized(), 'The form is not synchronized.');

        $this->assertEquals($formData['username'], $model->getUsername());
        $this->assertEquals($formData['email'], $model->getEmail());
    }
}