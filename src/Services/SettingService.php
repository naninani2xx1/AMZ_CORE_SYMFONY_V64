<?php

namespace App\Services;

use App\Core\Entity\Setting;

use App\Form\SettingCommonType;
use App\Form\SettingImgType;
use App\Form\SettingType;
use App\Security\Voter\SettingVoter;
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
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }


    public function add(Request $request): Response
    {

        $type = $request->query->get('type', 'common');
        $setting = new Setting();
        $this->denyAccessUnlessGranted(SettingVoter::CREATE,$setting);
        switch ($type) {
            case 'common':
                $form = $this->createForm(SettingCommonType::class, $setting, [
                    'action' => $this->generateUrl('app_admin_setting_add', ['type' => 'common']),
                ]);
                $template = 'Admin/views/setting/modals/form_common.html.twig';
                break;

            case 'size':
                $form = $this->createForm(SettingType::class, $setting, [
                    'action' => $this->generateUrl('app_admin_setting_add', ['type' => 'size']),
                ]);
                $template = 'Admin/views/setting/modals/form_size.html.twig';
                break;

            case 'img':
                $form = $this->createForm(SettingImgType::class, $setting, [
                    'action' => $this->generateUrl('app_admin_setting_add', ['type' => 'img']),
                ]);
                $template = 'Admin/views/setting/modals/form_img.html.twig';
                break;

            default:
                $this->addFlash('error', 'Không xác định được loại setting.');
                return $this->redirectToRoute('app_admin_setting_index');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($setting);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_setting_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }


    public function edit(Request $request, int $id): Response
    {
        $setting = $this->em->getRepository(Setting::class)->find($id);
        $this->denyAccessUnlessGranted(SettingVoter::EDIT,$setting);
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
        $this->denyAccessUnlessGranted(SettingVoter::DELETE,$setting);
        if ($setting) {
            $this->addFlash('success', 'Không thể xóa!');
        }
        return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
    }


}
