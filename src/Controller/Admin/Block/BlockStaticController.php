<?php

namespace App\Controller\Admin\Block;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\BlockDataType;
use App\Core\DTO\BlockDTO;
use App\Core\Entity\Block;
use App\Core\Services\BlockService;
use App\Core\Services\PageService;
use App\Form\Admin\Block\AddBlockForm;
use App\Form\Admin\Block\AddBlockStaticForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/block/static")
 */
class BlockStaticController extends AbstractController implements CRUDActionInterface
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
     * @Route(path="/", name="app_admin_block_static_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
       return $this->render('Admin/views/blockStatic/index.html.twig');
    }

    public function edit(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }

    public function delete(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/add", name="app_admin_block_static_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $block = new Block();
        $block->setKind(BlockDataType::KIND_STATIC);
        $form = $this->createForm(AddBlockStaticForm::class, $block, [
            'action' => $this->generateUrl('app_admin_block_static_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($block);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Block added successfully!']);
        }

        return $this->render('Admin/views/blockStatic/add_modal.html.twig', compact('form'));
    }
}