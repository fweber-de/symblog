<?php

namespace sb\ApiBundle\Controller;

use sb\ApiBundle\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use sb\DataBundle\Entity\Post;

/**
 * Class PostController
 * @package sb\ApiBundle\Controller
 */
class PostController extends AbstractApiController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction()
    {
        $posts = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findBy(array(), array('publishDate' => 'desc'));

        return $this->sendJSONResponse($posts);
    }

    public function objectAction($postId)
    {
        $post = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findOneById($postId);

        return $this->sendJSONResponse($post);
    }

    public function createAction(Request $request)
    {
        $post = $this->getObject($request->getContent(), 'sb\DataBundle\Entity\Post');

        $validator = $this->get('validator');
        $errors = $validator->validate($post);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return $this->throwJSONException($errorsString, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->sendJSONResponse($post);
    }

    public function updateAction(Request $request, $postId)
    {

    }

    public function deleteAction(Request $request, $postId)
    {

    }

}
