<?php

namespace App\Tests\Unit\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\LogUtils;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $logUtils;
    private $idCreatedTask;
    private $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUtils = new LogUtils($this->client);

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->purgeDatabase();
    }

    /**
     * Purge the database
     *
     * @return void
     * @throws ToolsException
     */
    private function purgeDatabase(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     * Test access tasks list
     *
     * @return void
     */
    public function testAccessTaskList()
    {
        $this->logUtils->login('user');
        $this->client->request('GET', "/tasks");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test access tasks list as admin
     *
     * @return void
     */
    public function testAccessTaskListAsAdmin(): void
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', "/tasks");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test create task
     *
     * @return void
     */
    public function testCreateTask()
    {
        $author = 'user';
        $title = 'Test title / add task';
        $content = 'Test content / add task';

        $this->logUtils->login($author);
        $crawler = $this->client->request('GET', "/tasks/create");
        $crsf = $crawler->filter('input[name="task[_token]"]')->extract(array('value'))[0];

        $this->client->request('POST', "/tasks/create", [
            'task' => [
                'title' => $title,
                'content' => $content,
                '_token' => $crsf
            ]
        ]);

        // check if task is created
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $taskCreated = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => $title]);

        // check if title task is present
        $this->assertEquals($title, $taskCreated->getTitle());

        // check if author task is present
        $this->assertEquals($author, $taskCreated->getUser()->getUsername());

        // check if content task is present
        $this->assertEquals($content, $taskCreated->getContent());
    }

    /**
     * Get the value of idCreatedTask
     *
     * @return int
     */
    public function getIdCreatedTask()
    {
        return $this->idCreatedTask;
    }

    /**
     * Set the value of idCreatedTask
     *
     * @param $idCreatedTask
     * @return  self
     */
    public function setIdCreatedTask($idCreatedTask): static
    {
        $this->idCreatedTask = $idCreatedTask;

        return $this;
    }

}