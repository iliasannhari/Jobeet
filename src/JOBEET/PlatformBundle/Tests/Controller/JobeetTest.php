<?php  

namespace JOBEET\PlatformBundle\Tests\Controller;

use JOBEET\PlatformBundle\Entity;
use PHPUnit\Framework\TestCase;
use JOBEET\PlatformBundle\Entity\Advert;


class JobeetTest extends TestCase
{
	public function testSlugify()
	{

		$adv = new Advert();
		$adv->setTitle('Maison');
		$adv->setAuthor('Maison anamemekd');
		$adv->setContent('Maison anamemekd');

		$this->assertEquals('Maison',$adv->getTitle() );
	}

	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/platform');

		 $this->assertTrue($crawler->filter('.jumborton)')->count() == 1);
	}

}