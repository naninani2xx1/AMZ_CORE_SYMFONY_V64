<?php

namespace App\Controller\Admin\Block;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\BlockDataType;
use App\Core\DTO\BlockDTO;
use App\Core\Entity\Block;
use App\Core\Services\BlockService;
use App\Core\Services\PageService;
use App\Form\Admin\Block\AddBlockForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/block")
 */
class BlockController extends AbstractController implements CRUDActionInterface
{
    private $pageService;
    private $blockService;
    private $entityManager;
    public function __construct(
        PageService $pageService,
        BlockService $blockService,
        EntityManagerInterface $entityManager
    )
    {
        $this->pageService = $pageService;
        $this->blockService = $blockService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/listing-of-page/{id}", name="app_admin_block_index", methods={"GET"})
     */
    public function listingOfPage(Request $request, $id): Response
    {
        $page = $this->pageService->findOneById((int)$id);
        return $this->render('Admin/views/block/index.html.twig', compact('page'));
    }


    public function index(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{pageId}/add", name="app_admin_block_add", methods={"GET","POST"}, requirements={"pageId"="\d+"})
     */
    public function addByPage(Request $request, int $pageId): Response
    {
        $page = $this->pageService->findOneById($pageId);
        $block = new Block();
        $block->setPost($page->getPost());
        $form = $this->createForm(AddBlockForm::class, $block, [
            'action' => $this->generateUrl('app_admin_block_add', ['pageId' => $pageId]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $block->setKind(BlockDataType::KIND_DYNAMIC);
            $this->entityManager->persist($block);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Block created successfully!']);
        }

        return $this->render('Admin/views/block/add_modal.html.twig', compact('form'));
    }

    public function edit(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }

    public function delete(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }

    public function add(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/update/{id}", name="app_admin_block_update", methods={"POST"})
     */
    public function update(#[MapRequestPayload] BlockDTO $blockDTO, int $id): Response
    {
        $block = $this->blockService->findOneById($id);
        $blockDTO->run($block);

        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Block updated successfully!']);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_block_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, int $id): Response
    {
        $block = $this->blockService->findOneById($id);
        $blocksType = $this->getParameter('blocks_type');
        $type = $blocksType[$block->getType()];

        return $this->forward($type['backend']['controller'], ['request' => $request, 'block' => $block]);
    }
}