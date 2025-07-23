<?php

namespace App\Core\Services;

use App\Core\Entity\Setting;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SettingService extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public function add(Request $request): RedirectResponse
    {
        $setting = new Setting();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);
        $settingCommon = new Setting();
        $formCommon = $this->createForm(SettingCommonType::class, $settingCommon);
        $formCommon->handleRequest($request);
        $settingImg = new Setting();
        $formImg=$this->createForm(SettingImgType::class, $settingImg);
        $formImg->handleRequest($request);
        if ($request->request->has('submit_setting') && $form->isSubmitted() && $form->isValid()) {
            $this->em->persist($setting);
            $this->em->flush();
        } elseif ($request->request->has('submit_common') && $formCommon->isSubmitted() && $formCommon->isValid()) {
            $this->em->persist($settingCommon);
            $this->em->flush();
        }
        elseif ($request->request->has('submit_img') && $formImg->isSubmitted() && $formImg->isValid()) {
            $this->em->persist($settingImg);
            $this->em->flush();
        }
        return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
    }


    public function edit(Request $request, int $id): Response
    {
        $setting = $this->em->getRepository(Setting::class)->find($id);
        if (!$setting) {
            throw new NotFoundHttpException('Setting không tồn tại.');
        }
        $form=$this->createForm(SettingType::class, $setting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Cập nhật thành công!');
            return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
        }

        return $this->render('Admin/views/setting/edit_modal.html.twig', [
            'form' => $form->createView(),
            'setting' => $setting,
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $setting = $this->em->getRepository(Setting::class)->find($id);
        if (!$setting) {
            throw new NotFoundHttpException('Không tìm thấy cài đặt để xóa.');
        }

        $this->em->remove($setting);
        $this->em->flush();

        $this->addFlash('success', 'Xóa thành công!');
        return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
    }
}
