<?php

namespace App\Controller\Admin;

use App\Form\SettingType;
use App\Core\Entity\Setting;
use App\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cms/setting')]
class SettingController extends AbstractController
{
    private SettingService $settingService;
    public function __construct(SettingService $settingService) {
        $this->settingService = $settingService;
    }

    #[Route(path: '/', name: 'app_admin_setting_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $settings = $em->getRepository(Setting::class)->findAll();
        $form = $this->createForm(SettingType::class, new Setting());

        return $this->render('admin/security/index.html.twig', [
            'settings' => $settings,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add', name: 'app_admin_setting_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        return $this->settingService->add($request);
    }

    #[Route('/edit/{id}', name: 'app_admin_setting_edit')]
    public function edit(Request $request, int $id): Response
    {
        return $this->settingService->edit($request, $id);
    }

    #[Route('/destroy/{id}', name: 'app_admin_setting_destroy')]
    public function destroy(int $id): Response
    {
        return $this->settingService->delete($id);
    }
}
