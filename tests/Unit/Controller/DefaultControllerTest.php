<?php

namespace App\Tests\Unit\Controller;

use AllowDynamicProperties;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[AllowDynamicProperties] class DefaultControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient();

    }

    public function testHomepage(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

}