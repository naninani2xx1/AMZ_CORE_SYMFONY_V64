<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\SettingRepository;

use App\Services\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cms/setting")
 */
class   SettingController extends AbstractController implements CRUDActionInterface
{

    public function __construct(private readonly SettingService $settingService
                            ,   private readonly SettingRepository $settingRepository
    )
    {

    }
    /**
     * @Route("/", name="app_admin_setting_index")
     */
    public function index(Request $request): Response
    {
        $settings = $this->settingRepository->findAll();
        return $this->render('Admin/views/setting/index.html.twig', [
            'settings' => $settings,
        ]);
    }

    /**
     * @Route("/add", name="app_admin_setting_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {

        return $this->settingService->add($request);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_setting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->settingService->edit($request, $id);
    }

    /**
     * @Route("/destroy/{id}", name="app_admin_setting_destroy")
     */
    public function delete(Request $request,int $id): Response
    {
        return $this->settingService->delete($id);
    }
}
