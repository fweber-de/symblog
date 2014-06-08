<?php

namespace sb\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        //get all posts which are not marked as draft
        $posts = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findBy(array('isDraft' => 0), array('publishDate' => 'desc'));

        //render
        return $this->get('template_controller')->renderTemplate('index.html.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showPostAction($slug)
    {
        //get post
        $post = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findOneBySlug($slug);

        //post not found
        if (!$post) {
            throw $this->createNotFoundException();
        }

        //if post is a draft, abort
        if ($post->getIsDraft() == true) {
            throw $this->createNotFoundException('This Post wasn\'t published yet!');
        }

        //render
        return $this->get('template_controller')->renderTemplate('post.html.twig', array(
            'post' => $post
        ));
    }

}
