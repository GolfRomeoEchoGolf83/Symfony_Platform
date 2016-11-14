<?php

namespace Greg\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('GregCoreBundle:core:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('info', "la page n'est pas disponible" );
        return $this->redirectToRoute('greg_core_homepage');
    }
}
