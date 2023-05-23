<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{

    #[Route('/home', name: 'app_home')]
public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $isLoggedIn = false;
    

    $users = $entityManager->getRepository(User::class)->findAll();
    $response = [];

    foreach ($users as $user) {
        $response[] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
        ];
    }
    if ($response != null) {
        $isLoggedIn = true;
    }

    return $this->json([
        'isLoggedIn' => $isLoggedIn,
        'users' => $response,
    ]);
}


    #[Route('/register', name: 'app_home_add',methods : ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($passwordHasher->hashPassword(
            $user,
            $request->request->get('password')
        ));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
        ],
        Response::HTTP_CREATED
    );
    }

    #[Route('/login', name: 'app_home_login',methods : ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $request->request->get('email')]);
        if (!$user || !$passwordHasher->isPasswordValid($user, $request->request->get('password'))) {
            return $this->json([
                'message' => 'Email or password is wrong.',
            ],
            Response::HTTP_UNAUTHORIZED
        );
        }
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
        ],
        Response::HTTP_OK
    );
    }

    #[Route('/logout', name: 'app_home_logout',methods : ['POST'])]
    public function logout(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        return $this->json([
            'message' => 'Logged out.',
        ],
        Response::HTTP_OK
    );
    }

    #[Route('/home/{id}', name: 'app_home_delete',methods : ['DELETE'])]
    public function deleteUser($id ,  EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
        ]);
    }
}
