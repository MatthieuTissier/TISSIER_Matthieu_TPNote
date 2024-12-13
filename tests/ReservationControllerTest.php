<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationControllerTest extends WebTestCase
{
    public function testCreateReservation(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/reservations', [
            'json' => [
                'date' => '2024-12-15 18:00:00',
                'timeSlot' => '18:00-20:00',
                'eventName' => 'Test Event',
                'user' => 1
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testGetReservation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/reservations/1');
        
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'eventName' => 'Test Event',
            'timeSlot' => '18:00-20:00',
        ]);
    }

    public function testUpdateReservation(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/reservations/1', [
            'json' => [
                'timeSlot' => '20:00-22:00',
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteReservation(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/reservations/1');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
