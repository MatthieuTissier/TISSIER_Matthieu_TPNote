<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'user@example.com',
                'password' => 'password',
                'name' => 'John Doe',
                'phoneNumber' => '0123456789'
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/1');
        
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'email' => 'user@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/users/1', [
            'json' => [
                'name' => 'John Smith',
                'phoneNumber' => '9876543210'
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/users/1');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
