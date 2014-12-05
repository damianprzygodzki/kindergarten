<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MessageController extends Controller
{
    /**
     * @Route("/getMessage/")
     */
    public function getMessageAction()
    {
        return array();
    }
}
