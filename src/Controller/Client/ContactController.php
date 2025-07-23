<?php
namespace App\Controller\Client;


use App\Entity\Contact;
use App\Form\Client\Contact\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="client/contact")
 */
Class ContactController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route(path="/", name="app_client_contact_index",methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact,[
            'action' => $this->generateUrl('app_client_contact_index'),
            'method' => 'POST',

        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($contact);
            $this->em->flush();
        }

        return $this->render('Client/views/contact/contact.html.twig', [
            'form' => $form,
        ]);
    }


}