<?php

namespace NoRegression\TestBundle\Tests\DataFixtures\ORM;

use NoRegression\TestBundle\DataFixtures\ORM\Reference;
use NoRegression\TestBundle\Tests\DataFixtures\TestFixtures\UserFixture;

class BaseFixtureTest extends \PHPUnit_Framework_TestCase
{

    public function testSetContainer()
    {
        $containerMock = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerInterface')
            ->getMock();

        $userFixture = new UserFixture();
        $userFixture->setContainer($containerMock);

        $this->assertSame($containerMock, $userFixture->getContainer());
    }

    public function testGetManager()
    {
        $managerMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $doctrineMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['getManager'])
            ->getMock();
        $doctrineMock->expects($this->once())
            ->method('getManager')
            ->willReturn($managerMock);

        $containerMock = $this->getMockBuilder('\Symfony\Component\DependencyInjection\Container')
            ->setMethods(['get'])
            ->getMock();

        $containerMock->expects($this->once())
            ->method('get')
            ->willReturn($doctrineMock);

        $userFixture = new UserFixture();
        $userFixture->setContainer($containerMock);

        $this->assertSame($userFixture->getManager(), $managerMock);
    }

    public function testReplaceMetaData()
    {
        $userFixtureMock = $this
            ->getMockBuilder('\NoRegression\TestBundle\Tests\DataFixtures\TestFixtures\UserFixture')
            ->setMethods(['getManager'])
            ->getMock();

        $entityMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $managerMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['getClassMetaData'])
            ->getMock();

        $metadataMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['setIdGenerator'])
            ->getMock();

        $userFixtureMock->method('getManager')
            ->willReturn($managerMock);

        $managerMock->method('getClassMetaData')
            ->willReturn($metadataMock);

        $metadataMock->expects($this->once())
            ->method('setIdGenerator');

        $metadataMock->generatorType = \Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_UUID;

        $userFixtureMock->replaceMetadata($entityMock);
    }

    public function testPersistEntity()
    {
        $userFixtureMock = $this
            ->getMockBuilder('\NoRegression\TestBundle\Tests\DataFixtures\TestFixtures\UserFixture')
            ->setMethods(['getManager', 'replaceMetadata'])
            ->getMock();

        $userFixtureMock->expects($this->once())
            ->method('replaceMetadata');

        $managerMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['persist'])
            ->getMock();

        $userFixtureMock->expects($this->once())
            ->method('getManager')
            ->willReturn($managerMock);

        $managerMock->expects($this->once())
            ->method('persist');


        $entityMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $userFixtureMock->persistEntity($entityMock);
    }

    public function testMapEntityData()
    {
        $userFixtureMock = $this
            ->getMockBuilder('\NoRegression\TestBundle\Tests\DataFixtures\TestFixtures\UserFixture')
            ->setMethods(['getManager', 'getReference'])
            ->getMock();

        $metadataMock = $this
            ->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadataInfo')
            ->disableOriginalConstructor()
            ->setMethods(['setFieldValue'])
            ->getMock();


        $managerMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['getClassMetaData'])
            ->getMock();

        $managerMock->method('getClassMetaData')
            ->willReturn($metadataMock);

        $userFixtureMock->expects($this->once())
            ->method('getManager')
            ->willReturn($managerMock);


        $referenceMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $userFixtureMock->expects($this->once())
            ->method('getReference')
            ->with('0b53e25c-e0f5-43a9-beb6-f7e937939d63')
            ->willReturn($referenceMock);


        $data = [
            'id' => '801ca46f-3fdf-4e5e-b62e-7a29c5b34e28',
            'reference' => new Reference('0b53e25c-e0f5-43a9-beb6-f7e937939d63')
        ];

        $entity = new \stdClass();

        $metadataMock->expects($this->exactly(2))
            ->method('setFieldValue')
            ->withConsecutive(
                [$entity, 'id', '801ca46f-3fdf-4e5e-b62e-7a29c5b34e28'],
                [$entity, 'reference', $referenceMock]
            );

        $userFixtureMock->mapEntityData($entity, $data);
    }

    public function testCreateEntities()
    {
        $userFixtureMock = $this
            ->getMockBuilder('\NoRegression\TestBundle\Tests\DataFixtures\TestFixtures\UserFixture')
            ->setMethods(['getManager', 'mapEntityData', 'persistEntity', 'addReference'])
            ->getMock();

        $managerMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['flush'])
            ->getMock();

        $entityMock = $this->getMockBuilder('NoRegression\TestBundle\Tests\DataFixtures\TestEntity\User')
            ->getMock();

        $userFixtureMock->expects($this->once())
            ->method('getManager')
            ->willReturn($managerMock);

        $userFixtureMock->expects($this->exactly(2))
            ->method('mapEntityData')
            ->willReturn($entityMock);

        $userFixtureMock->expects($this->exactly(2))
            ->method('persistEntity')
            ->with($entityMock);

        $userFixtureMock->expects($this->exactly(2))
            ->method('addReference');

        $managerMock->expects($this->once())
            ->method('flush');

        $data = [
            [
                'id' => '801ca46f-3fdf-4e5e-b62e-7a29c5b34e28',
                'email' => 'user001@example.com',
                'password' => 'password001'
            ], [
                'id' => 'bea18670-a7c9-11e5-a837-0800200c9a66',
                'email' => 'user002@example.com',
                'password' => 'password002'
            ]
        ];

        $userFixtureMock->createEntities($data, 'NoRegression\TestBundle\Tests\DataFixtures\TestEntity\User');
    }
}
