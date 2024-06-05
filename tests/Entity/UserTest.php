<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEmail()
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());
    }

    public function testUserPassword()
    {
        $user = new User();
        $password = 'password123';
        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());
    }

    public function testUserRoles()
    {
        $user = new User();
        $roles = ['ROLE_USER'];
        $user->setRoles($roles);
        $this->assertSame($roles, $user->getRoles());
    }
}
