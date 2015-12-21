<?php

namespace NoRegression\TestBundle\Tests\DataFixtures\ORM;

use NoRegression\TestBundle\DataFixtures\ORM\Id\UuidGenerator;

class UuidGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateWithExistingValue()
    {
        $uuidGenerator = $this
            ->getMockBuilder('\NoRegression\TestBundle\DataFixtures\ORM\Id\UuidGenerator')
            ->setMethods(['getGeneratedValue'])
            ->getMock();

        $uuidGenerator->expects($this->once())
            ->method('getGeneratedValue')
            ->willReturn('800e5210-a7cb-11e5-a837-0800200c9a66');

        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['getClassMetadata'])
            ->getMock();

        $metadataMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['getIdentifierFieldNames', 'getFieldValue', 'getGeneratedValue'])
            ->getMock();

        $metadataMock->method('getIdentifierFieldNames')
            ->willReturn(['id']);

        $entityManagerMock->method('getClassMetaData')
            ->willReturn($metadataMock);

        $entityMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $this->assertEquals('800e5210-a7cb-11e5-a837-0800200c9a66', $uuidGenerator->generate($entityManagerMock, $entityMock));
    }

    public function testGenerateWithoutExistingValue()
    {
        $uuidGenerator = $this
            ->getMockBuilder('\NoRegression\TestBundle\DataFixtures\ORM\Id\UuidGenerator')
            ->setMethods(['getGeneratedValue'])
            ->getMock();

        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['getClassMetadata'])
            ->getMock();

        $metadataMock = $this->getMockBuilder('\stdClass')
            ->setMethods(['getIdentifierFieldNames', 'getFieldValue', 'getGeneratedValue'])
            ->getMock();

        $metadataMock->method('getIdentifierFieldNames')
            ->willReturn(['id']);

        $entityManagerMock->method('getClassMetaData')
            ->willReturn($metadataMock);

        $entityMock = $this->getMockBuilder('\stdClass')
            ->getMock();

        $uuidGenerator->expects($this->once())
            ->method('getGeneratedValue')
            ->willReturn('d632d250-a7cc-11e5-a837-0800200c9a66');

        $this->assertEquals('d632d250-a7cc-11e5-a837-0800200c9a66', $uuidGenerator->generate($entityManagerMock, $entityMock));
    }
}
