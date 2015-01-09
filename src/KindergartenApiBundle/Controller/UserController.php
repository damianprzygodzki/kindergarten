<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/getReceivers")
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReceivers()
    {
        $em = $this->getDoctrine();
        $userRepo = $em->getRepository("KindergartenApiBundle:User");

        $users = $userRepo->findAll();

        $serializer = $this->get('jms_serializer');
        $usersJSON = $serializer->serialize($users, 'json');

        return new Response($usersJSON);
    }
}