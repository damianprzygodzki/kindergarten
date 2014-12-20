<?php

namespace KindergartenWebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/o-nas")
     * @Template()
     */
    public function aboutAction()
    {
        return array('name' => 'asd');
    }

    /**
     * @Route("/kontakt")
     * @Template()
     */
    public function contactAction()
    {
        return array();
    }
}
