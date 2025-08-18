<?php

namespace App\Service;

use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Factory\ConditionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait QueryListTrait
{
    private EntityManagerInterface $entityManager;

    /**
     * @template T of object
     * @param class-string<T> $entityClass
     * @param class-string $dtoClass
     * @param AbstractCondition[] $conditions
     * @param array<string, string>|null $orderBy
     * @param array<string, string>|null $fieldsMapping
     */
    protected function getEntityList(
        string $entityClass,
        string $dtoClass,
        int $start = 0,
        int $limit = 0,
        array $conditions = [],
        ?array $orderBy = null,
        ?array $fieldsMapping = null,
    ): Paginator {
        $selectParts = [];
        $fieldsMapping = $fieldsMapping ?? $this->generateDefaultMapping($entityClass, $dtoClass);

        foreach ($fieldsMapping as $entityField => $dtoField) {
            $selectParts[] = "e.{$entityField} AS {$dtoField}";
        }

        $selectPart = sprintf('NEW %s(%s)', $dtoClass, implode(', ', $selectParts));

        $qb = $this->entityManager->createQueryBuilder()
            ->select($selectPart)
            ->from($entityClass, 'e');

        foreach ($conditions as $condition) {
            $condition->apply($qb, 'e');
        }

        if ($orderBy) {
            foreach ($orderBy as $field => $direction) {
                $qb->addOrderBy('e.'.$field, $direction);
            }
        }

        $query = $qb->getQuery()
            ->setFirstResult($start);

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }


        return (new Paginator($query))->setUseOutputWalkers(false);
    }

    /**
     * @param class-string<T> $entityClass
     * @param class-string $dtoClass
     *
     * @throws \ReflectionException
     */
    private function generateDefaultMapping(
        string $entityClass,
        string $dtoClass
    ): array {
        $entityReflection = new \ReflectionClass($entityClass);
        $dtoReflection = new \ReflectionClass($dtoClass);

        $mapping = [];
        $dtoProperties = array_map(
            fn($p) => $p->getName(),
            $dtoReflection->getProperties()
        );

        foreach ($entityReflection->getProperties() as $property) {
            if (in_array($property->getName(), $dtoProperties)) {
                $mapping[$property->getName()] = $property->getName();
            }
        }

        return $mapping;
    }
}