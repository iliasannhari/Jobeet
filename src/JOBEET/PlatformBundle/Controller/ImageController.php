<?php

namespace JOBEET\PlatformBundle\Controller;

use JOBEET\PlatformBundle\Entity\Advert;
use JOBEET\PlatformBundle\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ImageController extends Controller
{
   /**
     * @Route("/editImage/{advertId}", name="jobeet_platform_editImage",requirements={"id" = "\d+"})
     *
     */
    public function editImageAction($advertId)
    {

    	$em = $this->getDoctrine()->getManager();

  // On récupère l'annonce
    	$advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($advertId);

  // On modifie l'URL de l'image par exemple
    	$advert->getImage()->setUrl('http://lescontournementsroutiers.be/images/voiture_qui_sourit_1.jpg');
      $advert->setContent("set BNPBANK SWISS recherchons un développeur Symfony débutant sur Lyon. Blabla…");


  // On n'a pas besoin de persister l'annonce ni l'image.
  // Rappelez-vous, ces entités sont automatiquement persistées car
  // on les a récupérées depuis Doctrine lui-même

  // On déclenche la modification
      $em->flush();

      return $this->render('JOBEETPlatformBundle:Image:edit_image.html.twig', array(
        'advert' => $advert
        ));
    }

  }
