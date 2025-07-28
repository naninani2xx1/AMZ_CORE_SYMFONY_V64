<?php

namespace App\Services\Admin;

use App\Entity\Manufacturer;
use App\Form\Admin\Manufacturer\ManufacturerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerService extends AbstractController
{

    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request): Response{
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer,['action' => $this->generateUrl('app_admin_manufacturer_add')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($manufacturer);
            $this->em->flush();
            return new JsonResponse(['message' => 'Manufacturer added successfully!']);
        }

        return $this->render('Admin/views/manufacturer/modals/form_add_manufacturer.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(Request $request,int $id): Response{
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($id);
        $form = $this->createForm(ManufacturerType::class, $manufacturer,['action' => $this->generateUrl('app_admin_manufacturer_edit', ['id' => $id])]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return new JsonResponse(['message' => 'Manufacturer updated successfully!']);
        }
        return $this->render('Admin/views/manufacturer/modals/form_edit_manufacturer.html.twig', [
            'form' => $form,
            'manufacturer' => $manufacturer,
        ]);
    }
    public function delete(Request $request,int $id): Response{
        $manufacturer = $this->em->getRepository(Manufacturer::class)->find($id);
        $manufacturer->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_manufacturer_index');
    }
}