<?php  

namespace JOBEET\PlatformBundle\Tests\Controller;

use JOBEET\PlatformBundle\Entity;
use JOBEET\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;




class JobeetTest extends WebTestCase
{
	public function testAdvert()
	{
		$client = self::createClient();

		$adv = new Advert();
		$adv->setTitle('Maison');
		$adv->setAuthor('Maison anamemekd');
		$adv->setContent('Maison anamemekd');

		$this->assertEquals('Maison',$adv->getTitle() );

	}

	public function testIndex()
	{
		$client = self::createClient();
		$crawler = $client->request('GET', '/');

		$this->assertTrue($crawler->filter('.jumbotron')->count() == 1);
		$this->assertEquals('JOBEET\PlatformBundle\Controller\AdvertController::indexAction', $client->getRequest()->attributes->get('_controller'));

		$adv = $this->getFirstAdvert();
		$this->assertEquals($adv->getId(), 13);
		// 13 is the first id on the table 


	}
	
	public function getFirstAdvert()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

		$query = $em->createQuery('SELECT j from JOBEETPlatformBundle:Advert j ');
		$query->setMaxResults(1);
		return $query->getSingleResult();
	}
	/*
	public function testJobForm(){
		$client = static::createClient(array(), array('HTTP_HOST' => 'localhost'));
		$client->followRedirects(true);


		$crawler = $client->request('GET', '/add');
		$this->assertEquals('JOBEET\PlatformBundle\Controller\AdvertController::addAction', $client->getRequest()->attributes->get('_controller'));

		$form = $crawler->selectButton('save')->form(array(
			
			'advert[title]' => 'Symfogrreny Rockgegs!',
			'advert[content]' => 'Symfogrny zzagerzazza!',
			'advert[author]' => 'zzzeae Roregcks!',
			'advert[categories][]' => 1
			));
		$client->submit($form);
		$this->assertEquals('JOBEET\PlatformBundle\Controller\AdvertController::addAction', $client->getRequest()->attributes->get('_controller'));


	}
	*/

	

}