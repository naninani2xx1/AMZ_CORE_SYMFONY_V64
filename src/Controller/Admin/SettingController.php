<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Entity\Setting;
use App\Core\Services\SettingService;
use App\Form\Admin\Setting\AddSettingForm;
use App\Form\Admin\Setting\EditSettingForm;
use App\Security\Voter\SettingVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cms/setting")
 */
class SettingController extends AbstractController implements CRUDActionInterface
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly EntityManagerInterface $entityManager
    )
    {

    }
    /**
     * @Route("/", name="app_admin_setting_index")
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted(SettingVoter::VIEW, null, 'Access denied');
        return $this->render('Admin/views/setting/index.html.twig');
    }

    /**
     * @Route("/add", name="app_admin_setting_add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $setting = new Setting();
        $this->denyAccessUnlessGranted(SettingVoter::VIEW, $setting, 'Access denied');
        $form = $this->createForm(AddSettingForm::class, $setting, [
            'action' => $this->generateUrl('app_admin_setting_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            AddSettingForm::settingSubmit($setting, $form);
            $this->entityManager->persist($setting);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Created Successfully'], Response::HTTP_CREATED);
        }
        elseif($form->isSubmitted() && !$form->isValid()){
            $messageError = "";
            $errors = $form->getErrors(true);
            /** @var FormError $error */
            foreach ($errors as $error) {
                if($error->getOrigin()->getConfig()->getName() == "add_setting_form")
                    break;
                $messageError = $error->getMessage();
                break;
                // Access error message: $error->getMessage()
                // Access error origin (field name): $error->getOrigin()->getName()
            }
            if(!empty($messageError))
                return new JsonResponse(['message' => $messageError], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->render('Admin/views/setting/add_modal.html.twig', compact('form'));
    }

    /**
     * @Route("/edit/{id}", name="app_admin_setting_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $setting = $this->settingService->findOneById($id);
        $this->denyAccessUnlessGranted(SettingVoter::EDIT, $setting, 'Access denied');
        $form = $this->createForm(EditSettingForm::class, $setting, [
            'action' => $this->generateUrl('app_admin_setting_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            AddSettingForm::settingSubmit($setting, $form);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Edited Successfully'], Response::HTTP_CREATED);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $messageError = "";
            $errors = $form->getErrors(true);
            /** @var FormError $error */
            foreach ($errors as $error) {
                if($error->getOrigin()->getConfig()->getName() == "edit_setting_form")
                    break;
                $messageError = $error->getMessage();
                break;
            }
            if(!empty($messageError))
                return new JsonResponse(['message' => $messageError], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->render('Admin/views/setting/edit_modal.html.twig', compact('form','setting'));
    }

    /**
     * @Route("/destroy/{id}", name="app_admin_setting_destroy")
     */
    public function delete(Request $request,int $id): Response
    {
        throw new \Exception('Not supported');
    }
}
