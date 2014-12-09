<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use KindergartenApiBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use KindergartenApiBundle\Entity\Classroom;

class ClassroomController extends Controller
{

    /**
     * @Route("/newClassroom")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newClassroom(Request $request)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();

        $teacher = $em->getRepository('KindergartenApiBundle:Teacher')->find($data['teacher']);

        $classroom = new Classroom();
        $classroom
            ->setName($data['name'])
            ->setTeacher($teacher);

        $em->persist($classroom);
        $em->flush();

        return new Response(200);
    }
    /**
     * @Route("/getAllClassroom")
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllClassroom()
    {
        $doctrine = $this->getDoctrine();
        $classrooms = $doctrine->getRepository('KindergartenApiBundle:Classroom')->findAll();

        $serializer = $this->get('jms_serializer');
        $classroomsJSON = $serializer->serialize($classrooms, 'json');

        return new Response($classroomsJSON);
    }


    /**
     * @Route("/removeClassroom")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeClassroom(Request $request)
    {
        $data = $request->request->all();

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $classroom = $doctrine->getRepository('KindergartenApiBundle:Classroom')->find($data["classroomId"]);

        $em->remove($classroom);
        $em->flush();

        return new Response(200);
    }
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

}