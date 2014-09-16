<?php

namespace sb\AdminBundle\Controller;

use sb\DataBundle\Entity\Post;
use sb\DataBundle\Helper\PostHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction()
    {
        $posts = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findBy(
            array(),
            array(
                'creationDate' => 'DESC'
            )
        );

        return $this->render(
            'sbAdminBundle:Post:collection.html.twig',
            array(
                'posts' => $posts
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();

        if ($request->get('sent', 0) == 1) {
            $post = new Post();
            $post
                ->setCreationDate(new \DateTime('now'))
                ->setText($request->get('text'))
                ->setTitle($request->get('title'))
                ->setSlug(PostHelper::generateSlug($request->get('title')))
                ->setPublishDate(new \DateTime($request->get('publishDate', 'now')))
                ->setAuthor($user);

            $validator = $this->get('validator');
            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                return $this->render('sbAdminBundle:Post:create.html.twig');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $session->getFlashBag()->add('notice', 'The Post was created!');

            return $this->redirect($this->generateUrl('admin_posts_collection'));
        }

        return $this->render('sbAdminBundle:Post:create.html.twig');
    }

    /**
     * @param Request $request
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $postId)
    {
        $session = $request->getSession();
        $post = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findOneById($postId);

        if (is_null($post)) {
            throw $this->createNotFoundException(sprintf('The requested Post was not found!'));
        }

        if ($request->get('sent', 0) == 1) {
            $post
                ->setEditDate(new \DateTime('now'))
                ->setText($request->get('text'))
                ->setTitle($request->get('title'))
                ->setSlug(PostHelper::generateSlug($request->get('title')))
                ->setPublishDate(new \DateTime($request->get('publishDate', 'now')));

            $validator = $this->get('validator');
            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                return $this->render(
                    'sbAdminBundle:Post:update.html.twig',
                    array(
                        'post' => $post
                    )
                );
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $session->getFlashBag()->add('notice', 'The Post was updated!');

            return $this->redirect($this->generateUrl('admin_posts_collection'));
        }

        return $this->render(
            'sbAdminBundle:Post:update.html.twig',
            array(
                'post' => $post
            )
        );
    }

    /**
     * @param Request $request
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $postId)
    {
        $session = $request->getSession();
        $post = $this->getDoctrine()->getRepository('sbDataBundle:Post')->findOneById($postId);

        if (is_null($post)) {
            throw $this->createNotFoundException(sprintf('The requested Post was not found!'));
        }

        if ($request->get('sent', 0) == 1) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

            $session->getFlashBag()->add('notice', 'The Post was deleted!');

            return $this->redirect($this->generateUrl('admin_posts_collection'));
        }

        return $this->render(
            'sbAdminBundle:Post:delete.html.twig',
            array(
                'post' => $post
            )
        );
    }
}
