<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{


    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', []);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $req, SerializerInterface $serializer, EntityManagerInterface $emi, UserPasswordHasherInterface $hash): Response
    {
        $user = $serializer->deserialize($req->getContent(), User::class, 'json');
//        dd($user);
        $hashedPassword = $hash->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $emi->persist($user);
        $emi->flush();

        $data = ['user_register' => "success"];

        return $this->json(
            $data,
            200
        );
    }
}
