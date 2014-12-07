<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use KindergartenApiBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use KindergartenApiBundle\Entity\Child;

class ChildController extends Controller
{

    /**
     * @Route("/newChild")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newChild(Request $request)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $um = $this->get('fos_user.user_manager');

        $parent = $um->findUserByUsername($data['parent']);

        $child = new Child();
        $child
            ->setBirthdate($data['birthdate'])
            ->setFullname($data['fullname'])
            ->setChildParent($parent);

        $em->persist($child);
        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/removeChild")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeChild(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $child = $doctrine->getRepository('KindergartenApiBundle:Child')->find($data["childId"]);

        $em->remove($child);
        $em->flush();

        return new Response(200);
    }
}