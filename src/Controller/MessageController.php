<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class MessageController
 * @package App\Controller
 * @Route("/api")
 */
class MessageController extends AbstractController
{

    /**
     * 
     *@Route("/message", name="message")
     */
    public function index(MessageRepository $msgRepo): Response
    {
        $msgs = $msgRepo->findAll();
        $data = ['msgRepo' => $msgs];
        return $this->json(
            $data,
            200
        );
    }


    /**
     * 
     *@Route("/message/add", name="add_message", methods={"POST"})
     *@Route("/message/edit/{id}", name="edit_message", methods={"PATCH"})
     */
    public function add(Message $msgEdit = null,Request $req, SerializerInterface $serializer, EntityManagerInterface $emi): Response
    {
        $modeCreation = null;
        if (!$msgEdit){
            $modeCreation = true;
        }

        $msg = $serializer->deserialize($req->getContent(), Message::class, 'json');


        if ($modeCreation){
        $emi->persist($msg);
        }else{

        $msgEdit->setTitle($msg->getTitle());
        $msgEdit->setContent($msg->getContent());
        $emi->persist($msgEdit);
        }


        $emi->flush();

        if (!$modeCreation){
            $msg = $msgEdit;
        }
        $data = ['msgRepo' => $msg];


        return $this->json(
            $data,
            200
        );
    }

    /**
     * 
     *@Route("/message/delete/{id}", name="delete_message", methods={"DELETE"})
     */
    public function delete(Message $msg, EntityManagerInterface $emi): Response
    {

        $emi->remove($msg);
        $emi->flush();

        $data = 'successfully deleted';

        return $this->json(
            $data,
            200
        );
    }
}
