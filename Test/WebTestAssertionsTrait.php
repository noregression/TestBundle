<?php

namespace NoRegression\TestBundle\Test;

trait WebTestAssertionsTrait
{
    protected function assertJsonResponseEquals($expected, $actual)
    {
        $decoded = json_decode($actual, true);
        $this->assertEquals($expected, $decoded);
    }

    protected function assertContentTypeJson($message = null)
    {
        $this->assertContentType('application/json');
    }

    protected function assertContentType($type, $message = null)
    {
        $this->assertEquals(
            'application/json',
            $this->getClient()->getResponse()->headers->get('content-type'),
            $message
        );
    }

    protected function assertResponseOk()
    {
        $this->assertStatus(200, 205);
    }

    protected function assertResponseError()
    {
        $this->assertStatus(400, 417);
    }

    protected function assertResponseFailure()
    {
        $this->assertStatus(500, 505);
    }

    private function assertStatus($min, $max, $message = null)
    {
        $status = $this->getClient()->getResponse()->getStatusCode();

        $this->assertGreaterThanOrEqual($min, $status, $message);
        $this->assertLessThanOrEqual($max, $status, $message);
    }
}
