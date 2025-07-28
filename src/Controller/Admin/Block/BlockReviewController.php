<?php

declare(strict_types=1);

namespace App\Controller\Admin\Block;

use App\Core\Services\BlockService;
use App\Core\Services\PageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\WebLink\Link;

/**
 * @Route(path="/cms/block")
 */
class BlockReviewController extends AbstractController
{
    private $blockService;
    private $entityManager;
    private $parameterBag;
    public function __construct(
        BlockService $blockService,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag
    )
    {
        $this->blockService = $blockService;
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route(path="/review/{id}", name="app_admin_block_preview", methods={"GET"})
     */
    public function preview(Request $request, int $id): Response
    {
        $block = $this->blockService->findOneById($id);
        return $this->render('Admin/views/block/preview.html.twig', compact('block'));
    }

    /**
     * @Route(path="/run-review/{id}", name="app_admin_block_run_preview", methods={"GET"})
     */
    public function runPreview(Request $request, int $id): Response
    {
//        {#    {{ render( controller( 'App\\Controller\\Admin\\Block\\BlockReviewController::runPreview',{id: block.id, request:app.request} )) }}#}

        $block = $this->blockService->findOneById($id);
        $config = $this->parameterBag->get('blocks_type');
        $blockType = $config[$block->getType()];

        $templateReview = $blockType['backend']['view'];
        return $this->render($templateReview, compact('block'));
    }
}
