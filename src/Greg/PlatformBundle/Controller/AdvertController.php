<?php

/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 07/11/2016
 * Time: 15:16
 */

namespace OC\PlatformBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    /**
     * @param $page
     * @return mixed
     */
    public function indexAction($page)
    {
        if($page < 1)
        {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }
        return $this->render('GregPlatformBundle:Advert:index.html.twig');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function viewAction($id)
    {
        return $this->render('GregPlatformBundle:Advert:view.html.twig', array('id' => $id));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce enregistrée.');
            return $this->redirectToRoute('greg_platform_view', array('id' => 5));
        }
        return $this->render('GregPlatformBundle:Advert:add.html.twig');
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function editAction($id, Request $request)
    {
        if($request->isMethode('POST'))
        {
            $request->getSession()->getFlashBag()->add('notice', 'annonce modifiée.');
            return $this->redirectionToRoute('greg_platform_view', array('id' => 5));
        }
        return $this->render('GregPlatformBundle:Advert:edit.html.twig');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        return $this->render('GregPlatformBundle:Advert:delete.html.twig');
    }
}