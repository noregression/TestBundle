<?php

namespace NoRegression\TestBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use NoRegression\PHPUnit\CallableComparatorTrait;

abstract class WebTestCase extends BaseWebTestCase
{
    use CallableComparatorTrait;
    use IsolatedTestTrait;
    use WebTestAssertionsTrait;

    public function setUp()
    {
        parent::setUp();
        $this->setupCallableComparator();
        $this->rebuildDatabase();
        $this->client = static::$application->getKernel()->getContainer()->get('test.client');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->tearDownCallableComparator();
    }

    public static function setUpBeforeClass()
    {
        static::bootstrapApplication();
    }

    protected function getClient()
    {
        return $this->client;
    }
}
