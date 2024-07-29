<?php
/*
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    
    public function testLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $form = $client->getCrawler()->selectButton('Se connecter')->form();
        
        $form['username'] = 'user';
        $form['password'] = 'password123';

        $client->submit($form);

        $this->assertResponseRedirects('/');

    }
        

}*/