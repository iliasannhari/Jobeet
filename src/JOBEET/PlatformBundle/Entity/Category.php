<?php
// src/JOBEET/PlatformBundle/Entity/Category.php

namespace JOBEET\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="jobeet_category")
 * @ORM\Entity(repositoryClass="JOBEET\PlatformBundle\Entity\CategoryRepository")
 */
class Category
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="name", type="string", length=255)
   */
  private $name;

  public function getId()
  {
    return $this->id;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getName()
  {
    return $this->name;
  }
}
