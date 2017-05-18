<?php

namespace JOBEET\PlatformBundle\Controller;

use JOBEET\PlatformBundle\Entity\Advert;
use JOBEET\PlatformBundle\Entity\Image;
use JOBEET\PlatformBundle\Entity\Application;
use JOBEET\PlatformBundle\Entity\AdvertSkill;

use JOBEET\PlatformBundle\Form\AdvertType;
use JOBEET\PlatformBundle\Form\AdvertEditType;
use JOBEET\PlatformBundle\Form\ApplicationType;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// N'oubliez pas ce use pour l'annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;




class AdvertController extends Controller
{

   /**
     * @Route("/{page}", name="jobeet_platform_home",requirements={"page" = "\d+"}, defaults={"page" = 1})
     * 
     */

   public function indexAction($page)
   {
		/*
		$listAdverts = $this
		->getDoctrine()
		->getManager()
		->getRepository('JOBEETPlatformBundle:Advert')
		->getAdvertWithCategories(array('VR', 'FullStack'))
		;
		*/

		if ($page < 1) {
			throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 7;


    $colors = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('JOBEETPlatformBundle:Color')
    ->findAll()
    ;




    $listAdverts = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('JOBEETPlatformBundle:Advert')
    ->getAdvertsPage($page, $nbPerPage)
    ;
    $countAdvert =count($listAdverts);
		// On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

		 // Si la page n'existe pas, on retourne une 404


    if ($nbPages && $page > $nbPages) {
     throw $this->createNotFoundException("La page ".$page." n'existe pas.");
   }


   return $this->render('JOBEETPlatformBundle:Advert:index.html.twig', array(
     'listAdverts' => $listAdverts,
     'nbPages'     => $nbPages,
     'page'        => $page,
     'countAdvert' => $countAdvert,
     'colors' => $colors
     ));


 }


 /**
     * @Route("/bycolor/{color}/{page}", name="jobeet_platform_bycolor",defaults={"page" = 1})
     * 
     */

 public function indexByColorAction($page, $color)
 {
    /*
    $listAdverts = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('JOBEETPlatformBundle:Advert')
    ->getAdvertWithCategories(array('VR', 'FullStack'))
    ;
    */

    if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    $colors = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('JOBEETPlatformBundle:Color')
    ->findAll()
    ;

    $nbPerPage = 7;

    $listAdverts = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('JOBEETPlatformBundle:Advert')
    ->getAdvertsByColorPage($page, $nbPerPage,$color)
    ;
    $countAdvert =count($listAdverts);
    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

     // Si la page n'existe pas, on retourne une 404


    if ($nbPages && $page > $nbPages) {
     throw $this->createNotFoundException("La page ".$page." n'existe pas.");
   }


   return $this->render('JOBEETPlatformBundle:Advert:index.html.twig', array(
     'listAdverts' => $listAdverts,
     'nbPages'     => $nbPages,
     'page'        => $page,
     'countAdvert' => $countAdvert,
     'colors' => $colors
     ));


 }


   /**
     * @Route("/view/{id}", name="jobeet_platform_view",requirements={"id" = "\d+"})
     *
     */
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

	  /**
      * @Route("/add", name="jobeet_platform_add")
   	* @Security("has_role('ROLE_AUTEUR')")
   	*/
   	public function addAction( Request $request)
   	{
   		$advert = new Advert();
   		$form   = $this->get('form.factory')->create(AdvertType::class, $advert);

      $em = $this->getDoctrine()->getManager();
      $lowColor = $em
      ->getRepository('JOBEETPlatformBundle:Color')
      ->findOneBy(array('name' => "Rouge"))
      ;



      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $advert->setColor($lowColor);       
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

        return $this->redirectToRoute('jobeet_platform_view', array('id' => $advert->getId()));
      }


      $logger = $this->get('logger');
      $logger->info('I just got the logger');
      $logger->error('An error occurred');

      $logger->critical('I left the oven on!', array(
        // include extra "context" info in your logs
        'cause' => 'in_hurry',
        ));



      return $this->render('JOBEETPlatformBundle:Advert:add.html.twig', array(
        'form' => $form->createView(),
        ));

    }


      /**
      * @Route("/edit/{id}", name="jobeet_platform_edit")
      * @Security("has_role('ROLE_AUTEUR')")
      */

      public function editAction( Request $request, $id)
      {
       $em = $this->getDoctrine()->getManager();
       $advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

       if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $form   = $this->get('form.factory')->create(AdvertEditType::class, $advert);

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			// Inutile de persister ici, Doctrine connait déjà notre annonce
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

        return $this->redirectToRoute('jobeet_platform_view', array('id' => $advert->getId()));
      }



      return $this->render('JOBEETPlatformBundle:Advert:edit.html.twig', array(
        'advert' => $advert,
        'form' => $form->createView(),
        ));

    }


    /**
      * @Route("/apply/{id}", name="jobeet_platform_apply")
      * @Security("has_role('ROLE_AUTEUR')")
      */

    public function applyAction( Request $request, $id)
    {
     $em = $this->getDoctrine()->getManager();
     $advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

     $application = new Application();
     $form   = $this->get('form.factory')->create(ApplicationType::class, $application);

     if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $application->setAdvert($advert);       
      $em->persist($advert);
      $em->persist($application);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'apply bien enregistrée.');

      return $this->redirectToRoute('jobeet_platform_view', array('id' => $id));
    }



    return $this->render('JOBEETPlatformBundle:Advert:apply.html.twig', array(
      'form' => $form->createView(),
      ));

  }

      /**
      * @Route("/delete/{id}", name="jobeet_platform_delete")
      * @Security("has_role('ROLE_AUTEUR')")
      */

      public function deleteAction(Request $request,$id)
      {
       $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
       $advert = $em->getRepository('JOBEETPlatformBundle:Advert')->find($id);

       if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create();

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em->remove($advert);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");
        return $this->redirectToRoute('jobeet_platform_home');
      }

      return $this->render('JOBEETPlatformBundle:Advert:delete.html.twig', array(
        'advert' => $advert,
        'form'   => $form->createView(),
        ));
    }

    public function menuAction($limit)
    {
     $em = $this->getDoctrine()->getManager();

     $listAdverts = $em->getRepository('JOBEETPlatformBundle:Advert')->findBy(
      		array(),                 // Pas de critère
      		array('date' => 'desc'), // On trie par date décroissante
      		$limit,                  // On sélectionne $limit annonces
      		0                        // À partir du premier
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
