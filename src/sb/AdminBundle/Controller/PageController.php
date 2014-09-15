<?php

namespace sb\AdminBundle\Controller;

use sb\DataBundle\Entity\Post;
use sb\DataBundle\Helper\PostHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('admin_dashboard'));
    }

    public function dashboardAction()
    {
        return $this->render('sbAdminBundle:Page:dashboard.html.twig');
    }
}
