<?php

namespace KindergartenWebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class TeacherController
 * @package KindergartenWebBundle\Controller
 *
 * @Route("/teacher")
 */
class TeacherController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function defaultAction()
    {
        return array(
                // ...
            );
    }

}
