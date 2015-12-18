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
        $this->assertEquals(200, $this->getClient()->getResponse()->getStatusCode());
    }
}
