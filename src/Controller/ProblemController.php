<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Problem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Serializer\SerializerInterface;
class ProblemController extends AbstractController
{
    #[Route('/problem', name: 'app_problem')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProblemController.php',
        ]);
    }

    #[Route('/problems', name: 'app_problem',methods : ['GET'])]
    public function listProblems(EntityManagerInterface $entityManager)
    {
        $problems = $entityManager->getRepository(Problem::class)->findAll();

        $response = [];

        foreach ($problems as $problem) {
            $response[] = [
                'id' => $problem->getId(),
                'title' => $problem->getTitle(),
                'description' => $problem->getDescription(),
                'dateCreated' => $problem->getDateCreated(),
                'dateModified' => $problem->getDateModified(),
                'user' => $problem->getUser()->getId(),
                'image' => $problem->getImage(),
                
                
            ];
        }

        return $this->json($response);
    }
    #[Route('/problems', name: 'app_problem_add',methods : ['POST'])]
    
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {



        $problem = new Problem();
        $problem->setTitle($request->request->get('title'));
        $problem->setDescription($request->request->get('description'));
        $problem->setDateCreated(new \DateTime($request->request->get('dateCreated')));
        $problem->setDateModified(new \DateTime($request->request->get('dateModified')));
        $developer = $entityManager->getRepository(User::class)->find($request->request->get('user'));
        $problem->setUser($developer);
        $file = $request->files->get('image');
    if ($file) {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->getParameter('image_directory'), $filename);
        $problem->setImage($filename);
    }

        $entityManager->persist($problem);
        $entityManager->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);

        return $this->json('inserted successfully');
    }
    #[Route('/problems/{id}', name: 'app_problem_delete',methods : ['DELETE'])]
    public function deleteProblem($id ,  EntityManagerInterface $entityManager)
    {
        $problem = $entityManager->getRepository(Problem::class)->find($id);

        $entityManager->remove($problem);
        $entityManager->flush();

        return $this->json([
            'id' => $problem->getId(),
            'title' => $problem->getTitle(),
            'description' => $problem->getDescription(),
            'dateCreated' => $problem->getDateCreated(),
            'dateModified' => $problem->getDateModified(),
            'user' => $problem->getUser()->getId(),
            
        ]);
    }
    /*#[Route('/problem/{id}', name: 'app_problem_update',methods : ['PUT'])]
    public function updateProblem($id , Request $request, EntityManagerInterface $entityManager)
    {
        $problem = $entityManager->getRepository(Problem::class)->find($id);
        $problem->setTitle($request->request->get('title'));
        $problem->setDescription($request->request->get('description'));
        $problem->setDateCreated(new \DateTime($request->request->get('dateCreated')));
        $problem->setDateModified(new \DateTime($request->request->get('dateModified')));

        // Assuming that the request body contains a developer ID, you can load the
        // developer entity from the database and set it on the problem entity:
        $developer = $entityManager->getRepository(Developer::class)->find($request->request->get('developer'));
        $problem->setDeveloper($developer);
        $entityManager->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);

        return $this->json('Updated successfully');
    }*/
    #[Route('/problems/{id}', name: 'app_problem_update',methods : ['PUT'])]
    public function updateProblem($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $problem = $entityManager->getRepository(Problem::class)->find($id);

        $problem->setTitle($request->request->get('title'));
        $problem->setDescription($request->request->get('description'));
        $problem->setDateCreated(new \DateTime($request->request->get('dateCreated')));
        $problem->setDateModified(new \DateTime($request->request->get('dateModified')));

        // Assuming that the request body contains a developer ID, you can load the
        // developer entity from the database and set it on the problem entity:
        $developer = $entityManager->getRepository(User::class)->find($request->request->get('user'));
        $problem->setUser($developer);

        $file = $request->files->get('image');
        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);
            $problem->setImage($filename);
        }

        $entityManager->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);

        return $this->json([
            'id' => $problem->getId(),
            'title' => $problem->getTitle(),
            'description' => $problem->getDescription(),
            'dateCreated' => $problem->getDateCreated(),
            'dateModified' => $problem->getDateModified(),
            'user' => $problem->getUser()->getId(),
            
        ]);
    }

    #[Route('/problems/{id}', name: 'app_problem_get',methods : ['GET'])]
    public function getProblem($id, EntityManagerInterface $entityManager)
    {
        $problem = $entityManager->getRepository(Problem::class)->find($id);

        return $this->json([
            'id' => $problem->getId(),
            'title' => $problem->getTitle(),
            'description' => $problem->getDescription(),
            'dateCreated' => $problem->getDateCreated(),
            'dateModified' => $problem->getDateModified(),
            'user' => $problem->getUser()->getId(),
            
        ]);
    }
}

