<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Repository\ManufacturerRepository;
use App\Services\ManufacturerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/admin/manufacturer")
 */

Class ManufacturerController extends AbstractController implements CRUDActionInterface
{
    public function __construct(EntityManagerInterface $em, ManufacturerService $manufacturerService,ManufacturerRepository $manufacturerRepository)
    {
        $this->manufacturerService = $manufacturerService;
        $this->em = $em;
        $this->manufacturerRepository = $manufacturerRepository;
    }

    /**
     * @Route(path="/", name="app_admin_manufacturer_index")
     */
    public function index(Request $request): Response
    {
        $data=$this->manufacturerRepository->findAllManufacturer();
        return  $this->render('Admin/views/manufacturer/index.html.twig', [
            'manufacturers' => $data,
        ]);
    }

    /**
     * @Route(path="/add", name="app_admin_manufacturer_add")
     */
    public function add(Request $request): Response
    {
        return $this->manufacturerService->add($request);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_manufacturer_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->manufacturerService->edit($request, $id);
    }

    /**
     * @Route(path="/delete/{id}", name="app_admin_manufacturer_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->manufacturerService->delete($request, $id);
    }

}