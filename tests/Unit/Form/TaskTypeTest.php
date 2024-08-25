<?php

namespace App\Tests\Unit\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

class TaskTypeTest extends TypeTestCase
{
    /**
     * Test if the form is built with the correct fields and options.
     *
     * @return void
     */
    public function testBuildForm(): void
    {
        $model = new Task();
        $form = $this->factory->create(TaskType::class, $model);

        $formView = $form->createView();

        $this->assertArrayHasKey('title', $formView->children);
        $this->assertArrayHasKey('content', $formView->children);
        $this->assertArrayHasKey('isDone', $formView->children);

        $this->assertInstanceOf(TextType::class, $form->get('title')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(TextareaType::class, $form->get('content')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(RadioType::class, $form->get('isDone')->getConfig()->getType()->getInnerType());
    }

    /**
     * Test form submission and data binding.
     *
     * @return void
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'My Task Title',
            'content' => 'This is the content of the task.',
            'isDone' => true,
        ];

        $user = new User();

        $model = new Task();
        $form = $this->factory->create(TaskType::class, $model);
        $model->setCreatedAt(new \DateTime());
        $model->toggle(true);
        $model->setUser($user);


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized(), 'The form is not synchronized.');

        $this->assertEquals($formData['title'], $model->getTitle());
        $this->assertEquals($formData['content'], $model->getContent());
        $this->assertEquals($formData['isDone'], $model->isDone());
        $this->assertEquals($user, $model->getUser());
        $this->assertInstanceOf(\DateTime::class, $model->getCreatedAt());
    }
}