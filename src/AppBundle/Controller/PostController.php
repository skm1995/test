<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function indexAction(Post $post, Request $request)
    {

        $form = null;

        if ($user = $this->getUser()) {

            $comment = new Comment();
            $comment->setPost($post);
            $comment->setUser($user);

            $form = $this->createForm(new CommentType(), $comment);
            $form->handleRequest($request);

            if ($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('succes', 'Dodano komentarz');

                return $this->redirectToRoute('post', array('id'=>$post->getId()));
            }

        }
        else{

            $comment = new Comment();
            $comment->setPost($post);

            $form = $this->createForm(new CommentType(), $comment);
            $form->handleRequest($request);

            if ($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('succes', 'Dodano komentarz');

                return $this->redirectToRoute('post', array('id'=>$post->getId()));
            }

        }



        return $this->render('default/post.html.twig', array(
            'post' => $post,
            'form' => is_null ($form) ? $form : $form->createView()
        ));
    }
}