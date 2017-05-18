<?php
// src/JOBEET/PlatformBundle/DataFixtures/ORM/LoadSkill.php

namespace JOBEET\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JOBEET\PlatformBundle\Entity\Color;

class LoadColor implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms de compétences à ajouter
    $names = array('Vert', 'Orange', 'Rouge',);

    foreach ($names as $name) {
      // On crée la compétence
      $color = new Color();
      $color->setName($name);

      // On la persiste
      $manager->persist($color);
    }

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }
}