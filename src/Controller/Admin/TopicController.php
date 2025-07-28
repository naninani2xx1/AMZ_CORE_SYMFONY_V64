<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Repository\TopicContactRepository;
use App\Services\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/admin/topic")
 */
class TopicController extends AbstractController implements CRUDActionInterface
{
    private TopicService $topicService;

    public function __construct(TopicService $topicService,
        private TopicContactRepository $topicRepository
    )
    {
        $this->topicService = $topicService;
    }

    /**
     * @Route(path="/", name="app_admin_topic_index")
     */
    public function index(Request $request): Response
    {
      $data=$this->topicRepository->findAllTopicContact();
      return  $this->render('Admin/views/topic/index.html.twig', [
          'topics' => $data,
      ]);
    }

    /**
     * @Route(path="/add", name="app_admin_topic_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        return $this->topicService->add($request);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_topic_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->topicService->edit($request, $id);
    }

    /**
     * @Route(path="/delete/{id}", name="app_admin_topic_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->topicService->delete($request, $id);
    }
}
