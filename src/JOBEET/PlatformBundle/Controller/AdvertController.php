<?php

namespace JOBEET\PlatformBundle\Controller;

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
    // Ici, on récupérera l'annonce correspondante à l'id $id
		$advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('JOBEETPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));
		
	}

	public function addAction( Request $request)
	{
    



		$advert = array(
      'title'   => 'Recherche développpeur Symfony',
      'id'      => 0,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('JOBEETPlatformBundle:Advert:add.html.twig', array(
      'advert' => $advert
    ));
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
