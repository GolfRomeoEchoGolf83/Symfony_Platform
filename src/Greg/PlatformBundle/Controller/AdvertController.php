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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            throw new NotFoundHttpException('Page "' .$page. '" inexistante.');
        }

        // fixation du nombre d'annonce par pages
        $nbPerPage = 3;

        // récupère l'objet paginator
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('GregPlatformBundle:Advert')
            ->getAdverts($page, $nbPerPage);

        // calcule le nombre total de pages avec count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        // si la page n'existe pas retourne 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException('Page "' .$page. '" inexistante.');
        }

        // appel de la vue
        return $this->render('GregPlatformBundle:Advert:index.html.twig', array(
            'listAdverts'   => $listAdverts,
            'nbPages'       => $nbPages,
            'page'          => $page
        ));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function viewAction($id)
    {
        // récupère une annonce avec find($id)
        $em = $this->getDoctrine()->getManager();

        $advert = $em
            ->getRepository('GregPlatformBundle:Advert')
            ->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " .$id. "n'existe pas.");
        }

        // récupère la liste des candidatures
        $listApplications = $em
            ->getRepository("GregPlatformBundle:Application")
            ->findBy(array('advert' => $advert));

        // récupère la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('GregPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert));

        return $this->render('GregPlatformBundle:Advert:view.html.twig', array(
            'advert'            => $advert,
            'listApplications'   => $listApplications,
            'listAdvertSkills'  => $listAdvertSkills,
        ));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        // création d'un objet Advert
        $advert = new Advert();

        // création d'un formbuilder avec form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

        // ajout des champs de l'entité que l'on veut dans le formulaire
        $formBuilder
            ->add('date',       DateType::class)
            ->add('title',      TextType::class)
            ->add('content',    TextareaType::class)
            ->add('author',     TextType::class)
            ->add('published',  CheckboxType::class)
            ->add('save',       SubmitType::class)
            ->getForm();

        // si la requête est en POST
        if ($request->isMethod('POST')) {
            // lien requête <-> formulaire
            // $advert contient les valeurs entrées dans le formulaire
            $form->handleRequest($request);

            // vérifie que les valeurs sont correctes
            if($form->isValid()) {
                // enregistre l'objet $advert dans la BDD
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'annonce enregistrée');

                // redirige vers la page de visualisation des annonces
                return $this->redirectToRoute('greg_platform_view', array(
                    'id'    => $advert->getId()
                ));
            }
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
            'form'   => $form->createView(),
        ));
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

        if($request->isMethode('POST'))
        {
            $request->getSession()->getFlashBag()->add('notice', 'annonce modifiée.');
            return $this->redirectionToRoute('greg_platform_view', array('id' => 5));

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
       $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository('GregPlatformBundle:Advert')->findBy(
            array(),
            array('date' => 'desc'),
            $limit,
            0
        );

        // le contrôleur passe les variables nécessaires au template
        return $this->render('GregPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}