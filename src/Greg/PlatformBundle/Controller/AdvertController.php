<?php

/**
 * Created by PhpStorm.
 * User: Greg
 * Date: 07/11/2016
 * Time: 15:16
 */

namespace Greg\PlatformBundle\Controller;

use Greg\PlatformBundle\Entity\Advert;
use Greg\PlatformBundle\Entity\AdvertSkill;
use Greg\PlatformBundle\Entity\Application;
use Greg\PlatformBundle\Entity\Image;
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
    public function viewAction($id, $listAdvertSkills, $listAdvertSkills)
    {
        // récupère l'annonce correspondant à l'id $id
        $em = $this->getDoctrine()->getManager();
        $advert = $em
            ->getRepository('GregPlatformBundle:Advert')
            ->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " .$id "n'existe pas.");
        }

        // récupère la liste des candidatures
        $listApplication = $em
            ->getRepository("GregPlatformBundle:Application")
            ->findBy(array('advert' => $advert));

        // récupère la liste des AdvertSkill
        $listApplication = $em
            ->getRepository('GregPlatformBundle:Skill')
            ->findBy(array('advert' => $advert));

        return $this->render('GregPlatformBundle:Advert:view.html.twig', array(
            'advert'            => $advert,
            'listApplication'   => $listApplication,
            'listAdvertSkills'  => $listAdvertSkills
        ));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        // récupère l'Entity Manager
        $em = $this->getDoctrine()->getManager();

        // création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant');

        // récupère les compétences
        $listSkills = $em->getRepository('GregPlatformBundle:Skill')->findAll();

        // pour chaque compétence
        foreach ($listSkills as $skill) {
            // nouvelle relation entre 1 annonce et 1 compétence
            $advertSkill = new AdvertSkill();

            // on la lie à l'annonce
            $advertSkill->setAdvert($advert);
            // on la lie à la compétence
            $advertSkill->setSkill($skill);

            // choix arbitraire sur le niveau de la compétence
            $advertSkill->setLevel('Expert');

            // on persiste
            $em->persist($advert);
        }
        $em->persist($advert);
        $em->flush();

        // creation de l'entité image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // creation d'une 1ere candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent('Jai les qualités requises');

        // creation d'une 2e candidature
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent('Je suis motivé');

        // liaison de l'annonce avec l'image
        $advert->setImage($image);

        // liaison des candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        // récupération de l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // 1: on persiste l'entité
        $em->persist($advert);

        // 1bis : on persiste l'entité application
        $em->persist($application1);
        $em->persist($application2);

        // 2: on flush ce qui a été persisté
        $em->flush();

        // si la requête est en POST, alors le visiteur a soumis le formulaire
        if($request->isMethod('POST'))
        {
            // création et gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Annonce enregistrée.');
            return $this->redirectToRoute('greg_platform_view', array('id' => $advert->getId()));
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
        $em = $this->getDoctrine()->getManager();

        // récupère l'annonce correspondant à $id
        $advert = $em->getRepository('GregPlatformBundle:Advert')->find($id);
        if(null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id" .$id. "n'existe pas");
        }

        // findAll retourne toutes les catégories de la BDD
        $listCategories = $em->getRepository('GregPlatformBundle:Category')->findAll();

        // boucle sur les catégories pour lier à l'annonce
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        // enregistrement
        $em->flush();


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
        $em = $this->getDoctrine()->getManager();

        // récupère l'annonce correspondant à $id
        $advert = $em->getRepository('GregPlatformBundle:Advert')->find($id);

        if(null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " .$id. " n'existe pas");
        }

        // boucle sur les annonces
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        // modification
        $em->flush();

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