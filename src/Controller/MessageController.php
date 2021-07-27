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
     *@Route("/message/add", name="add_message")
     */
    public function add(Request $req, SerializerInterface $serializer, EntityManagerInterface $emi): Response
    {

        $msg = $serializer->deserialize($req->getContent(), Message::class, 'json');
        $emi->persist($msg);
        $emi->flush();

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
