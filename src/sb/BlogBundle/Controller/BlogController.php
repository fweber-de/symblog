<?php

namespace sb\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{

    public function indexAction()
    {
        return $this->get('template_controller')->renderTemplate('index.html.twig');
    }

    public function showPostAction($slug, $postId)
    {
        $post = null;

        return $this->get('template_controller')->renderTemplate('post.html.twig', array(
            'post' => $post
        ));
    }

}
