<?php

namespace KindergartenWebBundle\Controller;

use KindergartenApiBundle\Entity\Child;
use KindergartenApiBundle\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use KindergartenApiBundle\Entity\Teacher;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/classrooms")
     * @Template()
     */
    public function classroomsAction()
    {
        $classrooms = $this->getDoctrine()->getRepository('KindergartenApiBundle:Classroom')->findAll();

        return array(
            'classrooms' => $classrooms
        );
    }

    /**
     * @Route("/addClassroom")
     * @Template()
     */
    public function addClassroomAction(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createFormBuilder($classroom)
            ->add('name', 'text')
            ->add('teacher', 'entity', array(
                'class' => 'KindergartenApiBundle:Teacher',
                'property' => 'fullname',
            ))
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/addTeacher")
     * @Template()
     */
    public function addTeacherAction(Request $request)
    {
        $teacher = new Teacher();

        $form = $this
            ->createForm('fos_user_registration')
            ->add('fullname', 'text')
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->flush();
        }

        return array(
            'form' => $form->createView()
        );
    }


    /**
     * @Route("/addChild")
     * @Template()
     */
    public function addChildAction(Request $request)
    {
        $child = new Child();

        $form = $this
            ->createFormBuilder($child)
            ->add('fullname', 'text')
            ->add('birthdate', 'date')
            ->add('classroom', 'entity', array(
                'class' => 'KindergartenApiBundle:Classroom',
                'property' => 'id',
            ))
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();
        }

        return array(
            'form' => $form->createView()
        );
    }



}
