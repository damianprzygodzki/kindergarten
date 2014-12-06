<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use KindergartenApiBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @Route("/receiveMessage/")
     */
    public function receiveMessageAction()
    {

        return array();
    }

    /**
     * @Route("/sendMessage/")
     */
    public function sendMessageAction(Request $request)
    {
        $data = $request->request->get('title');
        print_r($data);

        $em = $this->getDoctrine()->getManager();
        $um = $this->get('fos_user.user_manager');

        $sender = $um->findUserByUsername($data['sender']);
        $receiver = $um->findUserByUsername($data['receiver']);

        $message = new Message();
        $message
            ->setTitle($data['title'])
            ->setContent($data['content'])
            ->setSender($sender)
            ->setReceiver($receiver);

        $em->persist($message);
        $em->flush();

        return new Response(200);
    }
}
