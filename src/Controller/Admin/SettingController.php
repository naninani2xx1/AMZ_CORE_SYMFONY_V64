<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Form\SettingCommonType;
use App\Form\SettingImgType;
use App\Form\SettingType;
use App\Core\Entity\Setting;
use App\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cms/setting")
 */
class SettingController extends AbstractController implements CRUDActionInterface
{

    public function __construct(private readonly SettingService $settingService
                            ,   private readonly EntityManagerInterface $em
    )
    {

    }
    /**
     * @Route("/", name="app_admin_setting_index")
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('SETTING_VIEW');
        $settings = $this->em->getRepository(Setting::class)->findAll();
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
     * @Route("/edit/{id}", name="app_admin_setting_edit")
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
