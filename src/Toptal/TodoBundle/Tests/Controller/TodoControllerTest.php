<?php

namespace Toptal\TodoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testPost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testPut()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}');
    }

}
