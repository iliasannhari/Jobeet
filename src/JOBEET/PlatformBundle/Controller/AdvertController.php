<?php

namespace JOBEET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use Symfony\Component\HttpFoundation\JsonResponse;






class AdvertController extends Controller
{
	public function indexAction()
	{
		$content = $this->get('templating')->render('JOBEETPlatformBundle:Advert:index.html.twig');

		return new Response($content);	
	}

	public function linkAction()
	{
		$content = $this->get('templating')->render('JOBEETPlatformBundle:Advert:index.html.twig');

		return new Response($content);
	}

	public function viewAction($id)
	{
    $content = $this->get('templating')->render('JOBEETPlatformBundle:Advert:view.html.twig',array('id' => $id));

		return new Response($content);	
	}


	public function viewSlugAction($slug, $year, $format)
	{
		return new Response(
			"On pourrait afficher l'annonce correspondant au
			slug '".$slug."', créée en ".$year." et au format ".$format."."
			);
	}

	public function addAction(Request $request)
	{
		$session = $request->getSession();

    // Bien sûr, cette méthode devra réellement ajouter l'annonce

    // Mais faisons comme si c'était le cas
		$session->getFlashBag()->add('info', 'Annonce bien enregistrée');

    // Le « flashBag » est ce qui contient les messages flash dans la session
    // Il peut bien sûr contenir plusieurs messages :
		$session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

    // Puis on redirige vers la page de visualisation de cette annonce
		return $this->redirectToRoute('jobeet_platform_view', array('id' => 5));
	}
}
