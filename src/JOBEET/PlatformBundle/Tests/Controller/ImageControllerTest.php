<?php

namespace JOBEET\PlatformBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testEditimage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/editImage');
    }

}
