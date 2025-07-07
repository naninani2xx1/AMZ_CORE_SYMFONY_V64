<?php

namespace App\Core\DTO;

use App\Core\ValueObject\BaseRequestDTO;
use Symfony\Component\HttpFoundation\Request;

class RequestDataTableDTO extends BaseRequestDTO
{
    private $filter = null;
    private $limit = 1;
    private $page = 1;
    private $keyword = '';

    /**
     * @return mixed
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * @param null $filter
     */
    public function setFilter($filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function referenceObject(): string
    {
        return RequestDataTableDTO::class;
    }
}