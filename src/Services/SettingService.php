<?php

namespace App\Services;

use App\Core\Entity\Setting;
use App\Form\Admin\Setting\SettingCommonType;
use App\Form\Admin\Setting\SettingImgType;
use App\Form\Admin\Setting\SettingTopic;
use App\Form\Admin\Setting\SettingType;
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


    public function add(Request $request): Response
    {

        $type = $request->query->get('type', 'common');
        $setting = new Setting();
        switch ($type) {
            case 'common':
                $form = $this->createForm(SettingCommonType::class, $setting);
                $template = 'Admin/views/setting/modals/form_common.html.twig';
                break;

            case 'size':
                $form = $this->createForm(SettingType::class, $setting);
                $template = 'Admin/views/setting/modals/form_size.html.twig';
                break;

            case 'image':
                $form = $this->createForm(SettingImgType::class, $setting);
                $template = 'Admin/views/setting/modals/form_img.html.twig';
                break;
            case 'topic':
                $form = $this->createForm(SettingTopic::class, $setting);
                $template = 'Admin/views/setting/modals/form_topic.html.twig';
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
        if (!$setting) {
            throw new NotFoundHttpException('Setting không tồn tại.');
        }


        $type = $request->query->get('type', $setting->getSettingType());

        switch ($type) {
            case 'common':
                $form = $this->createForm(SettingCommonType::class, $setting);
                $template = 'Admin/views/setting/modals/form_edit_common.html.twig';
                break;

            case 'size':
                $form = $this->createForm(SettingType::class, $setting);
                $template = 'Admin/views/setting/modals/form_edit_size.html.twig';
                break;

            case 'image':
                $form = $this->createForm(SettingImgType::class, $setting);
                $template = 'Admin/views/setting/modals/form_edit_img.html.twig';
                break;
            case 'topic':
                $form = $this->createForm(SettingTopic::class, $setting);
                $template = 'Admin/views/setting/modals/form_edit_topic.html.twig';
                break;
            default:
                return $this->redirectToRoute('app_admin_setting_index');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('app_admin_setting_index');
        }

        return $this->render($template, [
            'form' => $form->createView(),
            'setting' => $setting,
        ]);
    }


    public function delete(int $id): RedirectResponse
    {
        $setting = $this->em->getRepository(Setting::class)->find($id);
        if (!$setting) {
            $this->addFlash('success', 'Không tìm thấy ');
        }
            $setting->setArchived(1);
        $this->em->flush();
        return new RedirectResponse($this->urlGenerator->generate('app_admin_setting_index'));
    }


}
