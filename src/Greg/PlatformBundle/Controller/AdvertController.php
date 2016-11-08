<?php

/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 07/11/2016
 * Time: 15:16
 */

namespace Greg\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    /**
     * @param $page
     * @return mixed
     */
    public function indexAction($page)
    {
        // une page doit toujours être >= 1
        if($page < 1)
        {
            // exception NotFoundHttpException : erreur 404
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }
        // récupère la liste des annonces avant de les passer au template
        // liste d'annonce en dur
        $listAdverts = array(
            array(
                'title'     => 'Recherche développeur Symfony',
                'id'        => 1,
                'author'    => 'Alexandre',
                'content'   => 'Nous recherchons un développeur Symfony débutant.',
                'date'      => new \DateTime()),

            array(
                'title'     => 'Mission de webmaster',
                'id'        => 2,
                'author'    => 'Hugo',
                'content'   => 'Nous recherchons un webmaster capable de maintenir notre site.',
                'date'      => new \DateTime()),

            array(
                'title'     => 'Offre de stage webdesigner',
                'id'        => 3,
                'author'    => 'Mathieu',
                'content'   => 'Nous proposons un poste de webdesigner.',
                'date'      => new \DateTime()),
        );

        // appel de template
        return $this->render('GregPlatformBundle:Advert:index.html.twig', array('listAdverts'=>array('listAdverts' => $listAdverts)));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function viewAction($id)
    {
        // récupère l'annonce correspondant à l'id $id
        $advert = array(
            'title'     => 'Recherche développeur Symfony',
            'id'        => $id,
            'author'    => 'Alexandre',
            'content'   => 'Nous recherchons un développeur Symfony débutant sur Nice.',
            'date'      => new \DateTime()
        );
        return $this->render('GregPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        // si la requête est en POST, alors le visiteur a soumis le formulaire
        if($request->isMethod('POST'))
        {
            // création et gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Annonce enregistrée.');
            return $this->redirectToRoute('greg_platform_view', array('id' => 5));
        }
        // si on n'est pas en POST alors on affiche le formulaire
        return $this->render('GregPlatformBundle:Advert:add.html.twig');
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function editAction($id, Request $request)
    {
        // récupère l'annonce correspondant à $id
        if($request->isMethode('POST'))
        {
            $request->getSession()->getFlashBag()->add('notice', 'annonce modifiée.');
            return $this->redirectionToRoute('greg_platform_view', array('id' => 5));

            $advert = array(
                'title'     => 'Recherche développeur Symfony',
                'id'        => $id,
                'author'    => 'Alexandre',
                'content'   => 'Nous recherchons un développeur débutant sur Nice',
                'date'      => new \DateTime()
            );
        }
        return $this->render('GregPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        // récupère l'annonce correspondant à $id

        // gère la suppression de l'annonce
        return $this->render('GregPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        // sera modifié par la récupération des data en BDD
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
        // le contrôleur passe les variables nécessaires au template
        return $this->render('GregPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}