<?php

declare(strict_types=1);

namespace App\Controller\EndUser;

use App\Core\Entity\TicketRequest;
use App\Form\EndUser\AddTicketRequestForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TicketRequestController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ticket-request/add", name="app_enduser_ticket_request_add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $ticket = new TicketRequest();
        $form = $this->createForm(AddTicketRequestForm::class, $ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Add successfully!'], Response::HTTP_OK);
        }
        return new JsonResponse(['message' => 'ok'], Response::HTTP_BAD_REQUEST);
    }
}
