<?php

namespace JOBEET\PlatformBundle\Controller;

use JOBEET\PlatformBundle\Entity\Advert;
use JOBEET\PlatformBundle\Entity\Image;
use JOBEET\PlatformBundle\Entity\Application;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;






class AdvertController extends Controller
{


	public function indexAction($page)
	{
     // Notre liste d'annonce en dur
		$listAdverts = array(
			array(
				'title'   => 'Recherche développpeur Symfony',
				'id'      => 1,
				'author'  => 'Alexandre',
				'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
				'date'    => new \Datetime()),
			array(
				'title'   => 'Mission de webmaster',
				'id'      => 2,
				'author'  => 'Hugo',
				'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
				'date'    => new \Datetime()),
			array(
				'title'   => 'Offre de stage webdesigner',
				'id'      => 3,
				'author'  => 'Mathieu',
				'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
				'date'    => new \Datetime())
			);
		return $this->render('JOBEETPlatformBundle:Advert:index.html.twig', array(
			'listAdverts' => $listAdverts
			));
	}

	public function viewAction($id)
	{
		
		$em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
    // ou null si l'id $id  n'existe pas, d'où ce if :
		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		$listApplications = $em
		->getRepository('JOBEETPlatformBundle:Application')
		->findBy(array('advert' => $advert))
		;

    // Le render ne change pas, on passait avant un tableau, maintenant un objet
		return $this->render('JOBEETPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications
    ));
	}

	public function addAction( Request $request)
	{
		// Création de l'entité Advert
		$advert = new Advert();
		$advert->setTitle('Recherche Fullstack developer Symfony.');
		$advert->setAuthor('Albert');
		$advert->setContent("BNPBANK SWISS recherchons un développeur Symfony débutant sur Lyon. Blabla…");

    // Création de l'entité Image
		$image = new Image();
		$image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
		$image->setAlt('Job de rêve');

    // On lie l'image à l'annonce
		$advert->setImage($image);

		// Création d'une première candidature
		$application1 = new Application();
		$application1->setAuthor('Marine');
		$application1->setContent("J'ai toutes les qualités requises.");

    // Création d'une deuxième candidature par exemple
		$application2 = new Application();
		$application2->setAuthor('Pierre');
		$application2->setContent("Je suis très motivé.");

    // On lie les candidatures à l'annonce
		$application1->setAdvert($advert);
		$application2->setAdvert($advert);


    // On récupère l'EntityManager
		$em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
		$em->persist($advert);

		// Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
    // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
		$em->persist($application1);
		$em->persist($application2);

    // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
    // on devrait persister à la main l'entité $image
    // $em->persist($image);

    // Étape 2 : On déclenche l'enregistrement
		$em->flush();
		

    // Reste de la méthode qu'on avait déjà écrit
		if ($request->isMethod('POST')) {
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
			return $this->redirectToRoute('jobeet_platform_view', array('id' => $advert->getId()));
		}

    // Si on n'est pas en POST, alors on affiche le formulaire
		return $this->render('JOBEETPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
	}

	public function editAction($id, Request $request)
	{

		$advert = array(
			'title'   => 'Recherche développpeur Symfony',
			'id'      => $id,
			'author'  => 'Alexandre',
			'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
			'date'    => new \Datetime()
			);

		return $this->render('JOBEETPlatformBundle:Advert:edit.html.twig', array(
			'advert' => $advert
			));


	}

	public function deleteAction($id)
	{
    // Ici, on récupérera l'annonce correspondant à $id

    // Ici, on gérera la suppression de l'annonce en question

		return $this->render('JOBEETPlatformBundle:Advert:delete.html.twig');
	}

	public function menuAction($limit)
	{
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
		$listAdverts = array(
			array('id' => 2, 'title' => 'Recherche développeur Symfony'),
			array('id' => 5, 'title' => 'Mission de webmaster'),
			array('id' => 9, 'title' => 'Offre de stage webdesigner')
			);

		return $this->render('JOBEETPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
			'listAdverts' => $listAdverts
			));
	}



}
