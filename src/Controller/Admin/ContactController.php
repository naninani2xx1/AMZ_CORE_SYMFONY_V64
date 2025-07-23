<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\SettingRepository;
use App\DataType\PostTypeChoice;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Services\ContactService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/admin/contact")
 */
Class ContactController extends AbstractController implements CRUDActionInterface
{
    private EntityManagerInterface $em;
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;

    }

    /**
     * @Route("/", name="app_admin_contact_index")
     */
    public function index(Request $request): Response
    {
        return $this->contactService->index($request);
    }
    /**
     * @Route("/add", name="app_admin_contact_add")
     */
    public function add(Request $request): Response{
        return new Response('add contact successfully');
    }


    /**
     * @Route("/update/{id}", name="app_admin_contact_update")
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->contactService->update($request,$id);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_contact_delete")
     */
    public function delete(Request $request,int $id): Response{
      return $this->contactService->delete($request,$id);
    }

}