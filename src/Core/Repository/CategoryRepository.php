<?php

namespace App\Core\Repository;

use App\Core\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    private readonly QueryBuilder $builder;

    const ALIAS = "category";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        $this->builder = $this->createQueryBuilder(self::ALIAS);
    }

    /**
     * Get all categories in a hierarchical order with level numbers.
     *
     * @return array
     */
    public function findAllHierarchical(): array
    {
        $categories = $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->getQuery()
            ->getResult();

        $result = [];
        $this->buildHierarchy($categories, $result, []);
        return $result;
    }

    /**
     * Build hierarchical structure with level numbers.
     *
     * @param array $categories
     * @param array &$result
     * @param array $level
     */
    private function buildHierarchy(array $categories, array &$result, array $level): void
    {
        /**
         * @var  Category $category
         */
        foreach ($categories as $index => $category) {
            $newLevel = array_merge($level, [$index + 1]);
            $category->setLevel((string) implode('.', $newLevel));
            $result[] = $category;

            $children = $this->createQueryBuilder('c')
                ->where('c.parent = :parent')
                ->setParameter('parent', $category)
                ->getQuery()
                ->getResult();

            if (!empty($children))
                $this->buildHierarchy($children, $result, $newLevel);
        }
    }



    public function findAllCategories()
    {

        return $this->builder->getQuery()->getResult();
    }

    /**
     * Tìm levelNumber lớn nhất của các danh mục gốc (parent là null)
     * @return string|null
     * @throws NonUniqueResultException
     */

    public function findMaxRootLevelNumber(): ?string
    {
        $result = $this->builder
            ->select("category.level")
            ->orderBy("category.level", 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['level'] ?? null;
    }

}