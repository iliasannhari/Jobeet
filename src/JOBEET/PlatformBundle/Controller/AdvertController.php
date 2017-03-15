<?php

namespace JOBEET\PlatformBundle\Controller;

use JOBEET\PlatformBundle\Entity\Advert;

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
		$advert = $this->getDoctrine()
		->getManager()
		->find('JOBEETPlatformBundle:Advert', $id)
		;

    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
    // ou null si l'id $id  n'existe pas, d'où ce if :
		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

    // Le render ne change pas, on passait avant un tableau, maintenant un objet
		return $this->render('JOBEETPlatformBundle:Advert:view.html.twig', array(
			'advert' => $advert
			));
	}

	public function addAction( Request $request)
	{
		// Création de l'entité
		$advert1 = new Advert();
		$advert1->setTitle('Recherche designer Symfony.');
		$advert1->setAuthor('Davis');
		$advert1->setContent("ADvertim recherchons un développeur Symfony débutant sur Lyon. Blabla…");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    // On récupère l'EntityManager
		$em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
		$em->persist($advert1);

		$advert2 = $em->getRepository('JOBEETPlatformBundle:Advert')->find(1);
		$advert2->setDate(new \Datetime());



    // Étape 2 : On « flush » tout ce qui a été persisté avant
		$em->flush();

    // Reste de la méthode qu'on avait déjà écrit
		if ($request->isMethod('POST')) {
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
			return $this->redirectToRoute('jobeet_platform_view', array('id' => $advert->getId()));
		}

    // Si on n'est pas en POST, alors on affiche le formulaire
		return $this->render('JOBEETPlatformBundle:Advert:add.html.twig', array('advert' => $advert1));
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
