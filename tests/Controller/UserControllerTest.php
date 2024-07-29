<?php
/*
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLoginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');


        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'admin';
        $form['password'] = 'password123';


        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->client->followRedirect();
    }

    public function testCreateAction(): void
    {
        $this->testLoginUser();

        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Maurice';
        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[email]'] = 'maurice@email.fr';
        $form['user[roles]'] = ['ROLE_USER'];


        $this->client->submit($form);

        $this->assertResponseRedirects('/users');

        $crawler = $this->client->followRedirect();


        $this->assertResponseIsSuccessful();

        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    public function testEditAction(): void
    {
        $this->testLoginUser();

        $crawler = $this->client->request('GET', '/users/18/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'Frederique';
        $form['user[password][first]'] = 'password123';
        $form['user[password][second]'] = 'password123';
        $form['user[email]'] = 'frederique@email.fr';
        
        $this->client->submit($form);

        $this->assertResponseRedirects('/users');

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

    public function testDeleteAction(): void
    {
        $this->testLoginUser();

        $this->client->request('GET', '/users/19/delete');

        $this->assertResponseRedirects('/users');
    }

    
}*/
