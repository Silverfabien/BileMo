<?php

namespace Tests\BileMoBundle\Controller\Api;

use Tests\BileMoBundle\Controller\Api\ApiTestCase;

class TokenControllerTest extends ApiTestCase
{
    public function testCreatedToken()
    {
        $this->createUser('test', 'testtest');

        $response = $this->client->post('api/token', [
            'auth' => ['test', 'testtest']
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists($response, 'token');

    }

}