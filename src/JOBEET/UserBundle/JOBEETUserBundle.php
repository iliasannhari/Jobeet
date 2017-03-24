<?php

namespace JOBEET\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JOBEETUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
