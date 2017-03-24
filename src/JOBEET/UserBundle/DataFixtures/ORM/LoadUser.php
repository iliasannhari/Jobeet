<?php
// src/JOBEET/UserBundle/DataFixtures/ORM/LoadUser.php

namespace JOBEET\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JOBEET\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('alex@gmail.com', 'marine@gmail.com', 'anna@gmail.com','ilias.annhari@gmail.com');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($name);
      $user->setPassword('password');
      $user->setEmail($name);
      $user->setEnabled(true);


      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_MODERATEUR'));

      // On le persiste
      $manager->persist($user);
    }

    $super_user = new User;
    $super_user->setUsername('david@gmail.com');
    $super_user->setPassword('password');
    $super_user->setEmail('david@gmail.com');
    $super_user->setEnabled(true);
    $super_user->setSalt('');
    $super_user->setRoles(array('ROLE_AUTEUR'));
    $manager->persist($super_user);

    // On déclenche l'enregistrement
    $manager->flush();
  }
}