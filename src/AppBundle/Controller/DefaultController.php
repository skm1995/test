<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        $date = date('Y-m-d H:i');
        $posts = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->from('AppBundle:Post', 'p')
            ->where('p.createdAt < :date')
            ->orderBy('p.createdAt', 'DESC')
            ->select('p')
            ->setParameter('date', $date);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $posts,
            $request->query->get('page', 1),
            5
        );

        return $this->render('default/index.html.twig', array(
            'posts' => $pagination
        ));
    }



}
