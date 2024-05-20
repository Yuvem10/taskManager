<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;

class WelcomeController extends AbstractController
{

    private $taskRepository;
    private $userRepository;
    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'welcomeNotConnected')]
    public function index(): Response
    {
        $user = $this->getUser();
        if(!empty($user)){
        $username = $user->getUsername();
        return $this->redirectToRoute('welcome', ['username' => $username]);
        }else{
        return $this->render('welcome/index.html.twig', ["username" => "Non connecté"]);
        }
    }

    #[Route('/connected/welcome', name: 'welcome')]
    public function welcome(): Response
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        return $this->render('welcome/welcome.html.twig', [
            'username' => $username
    ]);
    }

    #[Route('/connected/today', name: 'today')]
    public function today(): Response
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $userId = $user->getId();
        $currentDate = new \DateTime('now');
        $userTasks = $this->taskRepository->findBy(array(
        "user" =>  $userId,
        "date"     =>  $currentDate));

        return $this->render('welcome/today.html.twig', [
            'username' => $username,
            'tasks'    => $userTasks
    ]);
    }

    #[Route('/connected/planning', name: 'planning')]
    public function planning(): Response
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        return $this->render('welcome/planning.html.twig', [
            'username' => $username]);
    }

    #[Route('/connected/planif', name: 'planif')]
    public function planif(): Response
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $tasks = $this->taskRepository->findBy(["attribue_par" => $username]);
        return $this->render('welcome/planif.html.twig', [
            'username' => $username,
            'tasks'    => $tasks
        ]);
    }

    #[Route('/connected/planif/ajout', name: 'ajout')]
    public function add(Request $request, EntityManagerInterface $em) : Response
    {
        $task = new Task();
        $date = new \DateTime('now');
        $task->setDate($date);
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        $username = $user->getUsername();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $task->setFaite(false);
            $task->setAttribuePar($username);
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('planif');
        }

        return $this->render('forms/ajout.html.twig', ["form" => $form->createView(), "username" => $username]);
    }

    #[Route('/connected/planif/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em) : Response
    {
        $task = $this->taskRepository->findBy(["id" => $id]);
        $task = $task[0];
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser();
        $username = $user->getUsername();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('planif');
        }

        return $this->render('forms/ajout.html.twig', ["form" => $form->createView(), "username" => $username]);
    }

    #[Route('/connected/planif/delete/{id}', name: 'delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $em) : Response
    {
        $task = $this->taskRepository->findBy(["id" => $id]);
        $task = $task[0];
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('planif');
    }

    #[Route('/connected/today/validation/{id}', name: 'validation')]

    public function valid(int $id, EntityManagerInterface $em): Response 
    {
        $taskValid = $this->taskRepository->findBy(["id" => $id]);
        $valid = $taskValid[0]->isFaite();
        if ($valid == true){
            $taskValid[0]->setFaite(false);
        }else{
            $taskValid[0]->setFaite(true);
        }
        $em->persist($taskValid[0]);
        $em->flush();
        return $this->redirectToRoute('today');
    }




}
