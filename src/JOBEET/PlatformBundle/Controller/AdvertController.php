<?php

namespace JOBEET\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class AdvertController extends Controller
{
	public function indexAction()
	{
		// On veut avoir l'URL de l'annonce d'id 5.
		$url = $this->get('router')->generate(
            'jobeet_platform_view', // 1er argument : le nom de la route
            array('id' => 5),    // 2e argument : les valeurs des paramètres
            UrlGeneratorInterface::ABSOLUTE_URL
            );
        // $url vaut « /platform/advert/5 »
        // Depuis un contrôleur

		
		return new Response("L'URL de l'annonce d'id 5 est : ".$url);
	}

	public function linkAction()
	{
		$content = $this->get('templating')->render('JOBEETPlatformBundle:Advert:index.html.twig');

		return new Response($content);
	}

	public function viewAction($id)
	{
		return new Response("Affichage de l'annonce d'id : ".$id);
	}

	public function viewSlugAction($slug, $year, $format)
	{
		return new Response(
			"On pourrait afficher l'annonce correspondant au
			slug '".$slug."', créée en ".$year." et au format ".$format."."
			);
	}
}
