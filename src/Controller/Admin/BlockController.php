<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\ArticleRepository;
use App\Services\BlockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/block")
 */
class BlockController extends AbstractController implements CRUDActionInterface
{
    private BlockService $blockService;

    public function __construct(BlockService $blockService,
        private ArticleRepository $blockRepository
    )
    {
        $this->blockService = $blockService;
    }

    /**
     * @Route(path="/", name="app_admin_block_index")
     */
    public function index(Request $request): Response
    {
      $data=$this->blockRepository->findAllArticle();
      return  $this->render('Admin/views/block/index.html.twig', [
          'blocks' => $data,
      ]);
    }

    /**
     * @Route(path="/add", name="app_admin_block_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        return $this->blockService->add($request);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_block_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->blockService->edit($request, $id);
    }

    /**
     * @Route(path="/delete/{id}", name="app_admin_block_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->blockService->delete($request, $id);
    }
}
