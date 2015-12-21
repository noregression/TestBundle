<?php

namespace NoRegression\TestBundle\Tests\DataFixtures\TestFixtures;

use NoRegression\TestBundle\DataFixtures\ORM\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        //$this->createEntities($this->getFixtureData(), '\Atom\RestBundle\Entity\Node');
    }
}
