<?php

namespace sb\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
