<?php
/**
 * Created by PhpStorm.
 * User: nevada
 * Date: 27.02.14
 * Time: 12:48
 */

namespace Ant\MediaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\MediaBundle\Controller\GalleryController as BaseGalleryController;

use Ant\MediaBundle\Entity\Gallery;
use Ant\MediaBundle\Entity\GalleryRepository;

class GalleryController extends BaseGalleryController
{

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function indexAction()
    {
        $galleries = $this->get('sonata.media.manager.gallery')->findBy(array(
                'enabled' => true
            ),
            array('createdAt'=>'DESC')
        );



        return $this->render('AntMediaBundle:Gallery:index.html.twig', array(
            'galleries'   => $galleries,
        ));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function lastAction($max)
    {

        $galleries = $this->get('sonata.media.manager.gallery')->findBy(array(
                'enabled' => true
            ),
            array('createdAt'=>'DESC')
        );

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $galleries,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $max/*limit per page*/
        );

        return $this->render('AntMediaBundle:Gallery:last.html.twig', array(
            'galleries'   => $galleries,
            'pagination' => $pagination

        ));
    }

    /**
     * @param string $id
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function viewAction($id)
    {
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'id'      => $id,
            'enabled' => true
        ));

        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }



        return $this->render('AntMediaBundle:Gallery:view.html.twig', array(
            'gallery'   => $gallery
        ));
    }

    public function thumbAction($id)
    {
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'id'      => $id,
            'enabled' => true
        ));
        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }

        return $this->render('AntMediaBundle:Gallery:thumb.html.twig', array(
            'gallery'   => $gallery,
        ));
    }

    public function otherAction ($id, $max)
    {

        $em = $this->getDoctrine();
        $otherGalleries = $em->getRepository('AntMediaBundle:Gallery')->findOther($id,$max);
        return $this->render('AntMediaBundle:Gallery:other.html.twig', array(
            'otherGalleries'   => $otherGalleries,
        ));
    }


}
