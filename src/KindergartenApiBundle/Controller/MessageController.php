<?php

namespace KindergartenApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use KindergartenApiBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use KindergartenApiBundle\Entity\User as User;

class MessageController extends Controller
{
    /**
     * @Route("/getAllMessages")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllMessagesAction(Request $request)
    {

        /** @var User $user */
        $user = $this->get('security.context')->getToken()->getUser();

        if ( $user ) {
            $messages = $user->getMessagesSent();

            $serializer = $this->get('jms_serializer');
            $messagesJSON = $serializer->serialize($messages, 'json');
            return new Response($messagesJSON);
        }else{
            return new JsonResponse(array(
                'message' => 'Failed'
            ));
        }

    }

    /**
     * @Route("/sendMessage")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessageAction(Request $request)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $um = $this->get('fos_user.user_manager');


        $sender = $this->get('security.context')->getToken()->getUser();

        if($sender){
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

        }else{
            return new JsonResponse(array(
                'message' => 'Failed'
            ));
        }
    }


}
