<?php

namespace App\Services\Admin;

use App\Core\Entity\TicketRequest;
use App\Core\Repository\SettingRepository;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactService extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em, ContactRepository $contactRepository, SettingRepository $settingRepository)
    {
        $this->em=$em;
        $this->contactRepository=$contactRepository;
        $this->settingRepository=$settingRepository;

    }
    public function index(Request $request): Response{
//        $getTopics = $this->settingRepository->findTopics();
//        $choicesTopic = [];
//
//        foreach ($getTopics as $topic) {
//            $decode=json_decode($topic->getSettingValue());
//            $items = explode(',', $decode[0]);
//            foreach ($items as $item) {
//                $trimItems = trim($item);
//                $choicesTopic[$trimItems] = $trimItems;
//            }
//        }
//        $filters=$request->query->all();
//        $data = $this->contactRepository->findAllContact($filters,1,10);
        return $this->render('Admin/views/contact/index.html.twig');

    }
    public function update(Request $request,int $id): Response
    {
        $contact = $this->em->getRepository(TicketRequest::class)->findOneBy(['id'=>$id]);
        if (!$contact) {
            $this->addFlash('error', 'Contact not found');
        }
        $contact->setStatus(0);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_contact_index');
    }



    public function delete(Request $request,int $id): Response
    {
        $contact = $this->em->getRepository(TicketRequest::class)->findOneBy(['id'=>$id]);
        if (!$contact) {
            $this->addFlash('error', 'Contact not found');
        }
        $contact->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_contact_index');
    }

}