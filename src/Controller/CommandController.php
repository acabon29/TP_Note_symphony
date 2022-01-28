<?php

namespace App\Controller;

use App\Entity\Command;
use App\Repository\CommandRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//ajouter règle de validation d'email
class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     */
    public function index(CommandRepository $commandRepository, Request $request): Response
    {
        $commands = $commandRepository->findAll();
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'commands'        => $commands,
        ]);
    }

    /**
     * @Route("/command/{id}", name="command.show")
     */
    public function show($id): Response
    {
        $commandRepository = $this->getDoctrine()->getRepository(Command::class);
        $command = $commandRepository->find($id);
        if (!$command)
        {
            throw $this->createNotFoundException('The command does not exist');
        }
        return $this->render('command/show.html.twig', [
            'controller_name' => 'Guest',
            'command'        => $command,
        ]);
    }
}
