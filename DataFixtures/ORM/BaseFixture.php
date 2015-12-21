<?php

namespace NoRegression\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\ORM\Mapping\ClassMetadata;
use NoRegression\TestBundle\DataFixtures\ORM\Id\UuidGenerator;
use NoRegression\TestBundle\DataFixtures\ORM\Reference;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseFixture extends AbstractFixture implements ContainerAwareInterface
{
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function replaceMetadata($entity)
    {
        $manager = $this->getManager();
        $metadata = $manager->getClassMetaData(get_class($entity));

        if ($metadata->generatorType === ClassMetadata::GENERATOR_TYPE_UUID) {
            $metadata->setIdGenerator(new UuidGenerator());
        }
    }

    public function persistEntity($entity)
    {
        $manager = $this->getManager();
        $this->replaceMetadata($entity);
        $manager->persist($entity);
    }

    public function mapEntityData($entity, $data)
    {
        $manager = $this->getManager();
        $metadata = $manager->getClassMetaData(get_class($entity));

        foreach ($data as $key => $value) {
            if ($value instanceof Reference) {
                $value = $this->getReference($value->getId());
            }

            $metadata->setFieldValue($entity, $key, $value);
        }

        return $entity;
    }

    public function createEntities($fixtureData, $className)
    {
        $manager = $this->getManager();
        foreach ($fixtureData as $entityData) {
            $entity = $this->mapEntityData(new $className(), $entityData);

            $this->persistEntity($entity);
            $this->addReference($entity->getId(), $entity);
        }

        $manager->flush();
    }
}
