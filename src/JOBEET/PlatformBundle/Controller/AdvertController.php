<?php

namespace JOBEET\PlatformBundle\Controller;

use JOBEET\PlatformBundle\Entity\Advert;
use JOBEET\PlatformBundle\Entity\Image;
use JOBEET\PlatformBundle\Entity\Application;
use JOBEET\PlatformBundle\Entity\AdvertSkill;


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

		$listAdverts = $this
		->getDoctrine()
		->getManager()
		->getRepository('JOBEETPlatformBundle:Advert')
		->getAdvertWithCategories(array('VR', 'FullStack'))
		;

		foreach ($listAdverts as $advert) {
		    // Ne déclenche pas de requête : les candidatures sont déjà chargées !
		    // Vous pourriez faire une boucle dessus pour les afficher toutes
			$advert->getApplications();
		}


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

		 // On récupère maintenant la liste des AdvertSkill
		$listAdvertSkills = $em
		->getRepository('JOBEETPlatformBundle:AdvertSkill')
		->findBy(array('advert' => $advert))
		;

    // Le render ne change pas, on passait avant un tableau, maintenant un objet
		return $this->render('JOBEETPlatformBundle:Advert:view.html.twig', array(
			'advert'           => $advert,
			'listApplications' => $listApplications,
			'listAdvertSkills' => $listAdvertSkills
			));
	}

	public function addAction( Request $request)
	{
		// On récupère l'EntityManager
		$em = $this->getDoctrine()->getManager();

		
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

		// On récupère toutes les compétences possibles
		$listSkills = $em->getRepository('JOBEETPlatformBundle:Skill')->findAll();

    // Pour chaque compétence
		foreach ($listSkills as $skill) {
      // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
			$advertSkill = new AdvertSkill();

      // On la lie à l'annonce, qui est ici toujours la même
			$advertSkill->setAdvert($advert);
      // On la lie à la compétence, qui change ici dans la boucle foreach
			$advertSkill->setSkill($skill);

      // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
			$advertSkill->setLevel('Expert');

      // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
			$em->persist($advertSkill);
		}



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
		$em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
		$advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

    // La méthode findAll retourne toutes les catégories de la base de données
		$listCategories = $em->getRepository('JOBEETPlatformBundle:Category')->findAll();

    // On boucle sur les catégories pour les lier à l'annonce
		foreach ($listCategories as $category) {
			$advert->addCategory($category);
		}

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // Étape 2 : On déclenche l'enregistrement
		$em->flush();
		
		return $this->render('JOBEETPlatformBundle:Advert:edit.html.twig', array(
			'advert' => $advert
			));


	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
		$advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

    // On boucle sur les catégories de l'annonce pour les supprimer
		foreach ($advert->getCategories() as $category) {
			$advert->removeCategory($category);
		}

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // On déclenche la modification
		$em->flush();

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

	public function testAction()
	{
		$repository = $this
		->getDoctrine()
		->getManager()
		->getRepository('JOBEETPlatformBundle:Advert')
		;

		$listAdverts =$repository->myFindAll();
	}





}
