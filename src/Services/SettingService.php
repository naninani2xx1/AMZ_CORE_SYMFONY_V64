<?php

namespace App\Services;

use App\Core\Entity\Setting;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SettingService extends AbstractController
{
    private EntityManagerInterface $em;
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function add(Request $request): RedirectResponse
    {
        $setting = new Setting();
        $form = $this->formFactory->create(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($setting);
            $this->em->flush();

            $this->addFlash('success', 'Thêm cài đặt thành công!');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
    }

    public function edit(Request $request, int $id): Response
    {
        $setting = $this->em->getRepository(Setting::class)->find($id);
        if (!$setting) {
            throw new NotFoundHttpException('Setting không tồn tại.');
        }

        $form = $this->formFactory->create(SettingType::class, $setting);
        $value = $setting->getSettingValue();
        if (is_array($value)) {
            $form->get('value')->setData($value['value'] ?? '');
            $form->get('width')->setData($value['width'] ?? '');
            $form->get('height')->setData($value['height'] ?? '');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Cập nhật thành công!');
            return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
        }

        return $this->render('admin/security/edit_modal.html.twig', [
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
