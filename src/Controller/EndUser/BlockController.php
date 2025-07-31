<?php

declare(strict_types=1);

namespace App\Controller\EndUser;

use App\Core\Services\BlockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockController extends AbstractController
{
    private $blockService;
   public function __construct(BlockService $blockService)
   {
       $this->blockService = $blockService;
   }

    public function run(Request $request, int $id): Response
    {
        $block = $this->blockService->findOneById($id);
        $blocksType = $this->getParameter('blocks_type');
        $type = $blocksType[$block->getType()];
        return $this->render($type['frontend']['view'], ['request' => $request, 'block' => $block]);
    }
}
