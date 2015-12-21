<?php

namespace NoRegression\TestBundle\Tests\DataFixtures\TestEntity;

/**
 * @Entity
 */
class User
{
    /**
     * @Column(type="integer")
     * @Id
     */
    private $id;

    /**
     * @Column(length=255)
     */
    private $email;

    /**
     * @Column(length=32)
     */
    private $password;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
