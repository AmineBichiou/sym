<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Problem;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Entity\User;




class ResponseController extends AbstractController
{
    
    #[Route('/response', name: 'app_response_add',methods : ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $response = new Response();
        $response->setSolution($request->request->get('solution'));
        $response->setDateCreated(new \DateTime($request->request->get('dateCreated')));
        $response->setDateModified(new \DateTime($request->request->get('dateModified')));
        $developer = $em->getRepository(User::class)->find($request->request->get('user'));
        $response->setUser($developer);
        $problem = $em->getRepository(Problem::class)->find($request->request->get('problem'));
        $response->setProblem($problem);

        $em->persist($response);
        $em->flush();

        return $this->json(
            [
                'id' => $response->getId(),
                'solution' => $response->getSolution(),
                'dateCreated' => $response->getDateCreated(),
                'dateModified' => $response->getDateModified(),
                'user' => $response->getUser()->getId(),
                'problem' => $response->getProblem()->getId(),
            ],
            HttpFoundationResponse::HTTP_CREATED
        );
    }
    #[Route('/response/{id}', name: 'app_response_delete',methods : ['DELETE'])]
    public function deleteResponse($id ,  EntityManagerInterface $entityManager)
    {
        $response = $entityManager->getRepository(Response::class)->find($id);

        $entityManager->remove($response);
        $entityManager->flush();

        return $this->json([
            'id' => $response->getId(),
            'solution' => $response->getSolution(),
            'dateCreated' => $response->getDateCreated(),
            'dateModified' => $response->getDateModified(),
            'user' => $response->getUser()->getId(),
            'problem' => $response->getProblem()->getId(),
        ]);
    }
    #[Route('/response/{id}', name: 'app_response_update',methods : ['PUT'])]
    public function updateResponse($id , Request $request, EntityManagerInterface $entityManager)
    {
        $response = $entityManager->getRepository(Response::class)->find($id);

        $response->setSolution($request->request->get('solution'));
        $response->setDateModified(new \DateTime($request->request->get('dateModified')));
        $entityManager->flush();

        return $this->json([
            'id' => $response->getId(),
            'solution' => $response->getSolution(),
            'dateCreated' => $response->getDateCreated(),
            'dateModified' => $response->getDateModified(),
            'user' => $response->getUser()->getId(),
            'problem' => $response->getProblem()->getId(),
        ]);
    }

    #[Route('/response/{id}', name: 'app_response_get',methods : ['GET'])]
    public function getResponse($id ,  EntityManagerInterface $entityManager)
    {
        $response = $entityManager->getRepository(Response::class)->find($id);

        return $this->json([
            'id' => $response->getId(),
            'solution' => $response->getSolution(),
            'dateCreated' => $response->getDateCreated(),
            'dateModified' => $response->getDateModified(),
            'user' => $response->getUser()->getId(),
            'problem' => $response->getProblem()->getId(),
        ]);
    }
    #[Route('/response', name: 'app_response',methods : ['GET'])]
public function listResponses(EntityManagerInterface $entityManager)
{
    $responses = $entityManager->getRepository(Response::class)->findAll();

    $responseArray = [];

    foreach ($responses as $response) {
        $responseArray[] = [
            'id' => $response->getId(),
            'solution' => $response->getSolution(),
            'dateCreated' => $response->getDateCreated(),
            'dateModified' => $response->getDateModified(),
            'user' => $response->getUser()->getId(),
            'problem' => $response->getProblem()->getId(),
        ];
    }

    return $this->json($responseArray);
}
    

    
}
