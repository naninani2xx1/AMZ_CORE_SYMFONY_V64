<?php

namespace App\Twig\Runtime;

use App\Core\Entity\TicketRequest;
use App\Form\EndUser\AddTicketRequestForm;
use App\Repository\ContactRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class TicketRequestExtensionRuntime implements RuntimeExtensionInterface
{
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $urlGenerator;
    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        // Inject dependencies if needed
    }

    public function builderContactForm(): FormView
    {
        $ticket = new TicketRequest();
        return $this->formFactory->createBuilder(AddTicketRequestForm::class, $ticket, [
            'action' => $this->urlGenerator->generate('app_enduser_ticket_request_add'),
        ])->getForm()->createView();
    }
    public function filterTicketRequest(ContactRepository $contactRepository ,$filter=[]): array
    {
        $data = $contactRepository->findAllContact($filter);
        return $data;
    }
}
