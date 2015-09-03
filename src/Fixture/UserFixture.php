<?php

namespace Fixture;

use Edefine\Framework\Fixture\AbstractFixture;
use Entity\User;

class UserFixture extends AbstractFixture
{
    const USERS_TO_CREATE = 3;

    public function getOrder()
    {
        return 1;
    }

    public function load()
    {
        $manager = \ServiceFactory::getInstance()->getEntityManager();

        $admin = $this->createAdmin('FirstName', 'LastName', 'email@address.com', 'password');
        $manager->save($admin);

        for ($i = 1; $i <= self::USERS_TO_CREATE; $i++) {
            $user = $this->createUser("FirstName {$i}", "LastName {$i}", "email{$i}@address.com", "password{$i}");
            $manager->save($user);
        }
    }

    private function createUser($firstName, $lastName, $email, $password)
    {
        $user = new User();

        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPlainPassword($password)
            ->addRole('USER');

        return $user;
    }

    private function createAdmin($firstName, $lastName, $email, $password)
    {
        $user = $this->createUser($firstName, $lastName, $email, $password);

        $user->addRole('ADMIN');

        return $user;
    }
}