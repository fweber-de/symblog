<?php

namespace sb\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction(Request $request)
    {
        $posts = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findAll();

        return $this->render('sbAdminBundle:Post:collection.html.twig', array(
            'posts' => $posts
        ));
    }

    public function createAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->get('sent') == 1) {

        } else {

        }
    }

    public function updateAction(Request $request, $postId)
    {

    }

    public function deleteAction(Request $request, $postId)
    {

    }

}
