<?php

namespace KindergartenWebBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use KindergartenApiBundle\Entity\Child;
use KindergartenApiBundle\Entity\Classroom;
use KindergartenApiBundle\Entity\Message;
use KindergartenApiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BackendController
 * @package KindergartenWebBundle\Controller
 *
 * @Route("/backend")
 */
class BackendController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function defaultAction()
    {
        return array(// ...
        );
    }

    /**
     * @Route("/users")
     * @Template()
     */
    public function usersAction()
    {
        $um = $this->get('fos_user.user_manager');
        $users = $um->findUsers();

        $teachers = array();
        $parents = array();

        foreach($users as $user){
            if($user->getRoles()[0] == 'ROLE_ADMIN')
            {
                array_push($teachers, $user);
            }else{
                array_push($parents, $user);
            }
        }

        return array(
            'teachers' => $teachers,
            'parents' => $parents
        );
    }

    /**
     * @Route("/deleteUser/{username}")
     * @Template()
     */
    public function deleteUserAction($username)
    {
        $um = $this->get('fos_user.user_manager');
        $userRef = $um->findUserByUsername($username);

        $um->deleteUser($userRef);

        return $this->redirectToRoute('kindergartenweb_backend_users');
    }

    /**
     * @Route("/messages")
     * @Template()
     */
    public function messagesAction()
    {
        $messagesSent = $this->getUser()->getMessagesSent();
        $messagesReceived = $this->getUser()->getMessagesReceived();

        return array(
            'messagesSent' => $messagesSent,
            'messagesReceived' => $messagesReceived
        );
    }

    /**
     * @Route("/messages/show/{messageId}")
     * @Template()
     */
    public function showMessageAction($messageId)
    {
        $message = $this->getDoctrine()->getRepository('KindergartenApiBundle:Message')->find($messageId);

        $message->setReceived(1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return array(
            'message' => $message
        );
    }

    public function getUnreceivedCountAction()
    {
        $message = $this->getDoctrine()->getRepository('KindergartenApiBundle:Message')->findBy(
            array('receiver' => $this->getUser(), 'received' => false)
        );

        return new Response(count($message));
    }


    /**
     * @Route("/messages/delete/{messageId}")
     */
    public function deleteMessageAction($messageId)
    {
        $message = $this->getDoctrine()->getRepository('KindergartenApiBundle:Message')->find($messageId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($message);
        $em->flush();

        return $this->redirect($this->generateUrl('kindergartenweb_backend_messages'));
    }


    /**
     * @Route("/messages/new/{receiverUsername}")
     * @Template()
     */
    public function newMessageAction(Request $request, $receiverUsername = null)
    {
        $message = new Message();

        if ($receiverUsername == null) {

            $form = $this->createFormBuilder($message)
                ->add('title', 'text')
                ->add('receiver', 'entity', array(
                    'class' => 'KindergartenApiBundle:User',
                    'property' => 'fullname',
                ))
                ->add('content', 'textarea')
                ->add('submit', 'submit')
                ->getForm();
        } else {

            $form = $this->createFormBuilder($message)
                ->add('title', 'text')
                ->add('receiver', 'entity', array(
                    'class' => 'KindergartenApiBundle:User',
                    'property' => 'fullname',
                    'disabled' => true,
                    'data' => $receiverUsername
                ))
                ->add('content', 'textarea')
                ->add('submit', 'submit')
                ->getForm();
        }


        $form->handleRequest($request);

        if ($form->isValid()) {
            $message->setSender($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl('kindergartenweb_backend_messages'));
        }

        return array(
            'form' => $form->createView()
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
     * @Route("/removeClassroom/{classroomId}")
     * @Template()
     */
    public function removeClassroomAction($classroomId)
    {
        $classroom = $this->getDoctrine()->getRepository('KindergartenApiBundle:Classroom')->findOneBy(array("id" => $classroomId));

        $em = $this->getDoctrine()->getManager();
        $em->remove($classroom);
        $em->flush();

        return $this->redirectToRoute('kindergartenweb_backend_classrooms');
    }

    /**
     * @Route("/deleteChild/{childId}")
     */
    public function deleteChildAction($childId)
    {
        $classroom = $this->getDoctrine()->getRepository('KindergartenApiBundle:Child')->find($childId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($classroom);
        $em->flush();

        return $this->redirectToRoute('kindergartenweb_backend_classrooms');
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
                'class' => 'KindergartenApiBundle:User',
                'property' => 'fullname',
                // todo query na nauczycieli
            ))
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();

            return $this->redirect($this->generateUrl('kindergartenweb_backend_classrooms'));

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
        $teacher = new User();

        $em = $this->getDoctrine();

        $form = $this
            ->createForm('fos_user_registration', $teacher)
            ->add('fullname', 'text')
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $teacher->setEnabled(true);
            $em->getManager()->persist($teacher);
            $em->getManager()->flush();

            return $this->redirect($this->generateUrl('kindergartenweb_backend_users'));

        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/addParent")
     * @Template()
     */
    public function addParentAction(Request $request)
    {
        $parent = new User();

        $em = $this->getDoctrine();

        $form = $this
            ->createForm('fos_user_registration', $parent)
            ->add('fullname', 'text')
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $parent->setEnabled(true);
            $em->getManager()->persist($parent);
            $em->getManager()->flush();

            return $this->redirect($this->generateUrl('kindergartenweb_backend_users'));
        }

        return array(
            'form' => $form->createView()
        );
    }


    /**
     * @Route("/addChild/{classroomId}")
     * @Template()
     */
    public function addChildAction(Request $request, $classroomId)
    {
        $child = new Child();

        $classroom = $this->getDoctrine()->getRepository('KindergartenApiBundle:Classroom')->find($classroomId);
        $child->setClassroom($classroom);

        $form = $this
            ->createFormBuilder($child)
            ->add('fullname', 'text')
            ->add('birthdate', 'date')
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();

            return $this->redirect($this->generateUrl('kindergartenweb_backend_classrooms'));

        }

        return array(
            'form' => $form->createView()
        );
    }


}
