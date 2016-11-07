<?php

namespace Greg\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GregPlatformBundle:Default:index.html.twig');
    }
}
