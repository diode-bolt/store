<?php

namespace App\Query\Condition\Factory;

use App\Error\WrongFilterTypeException;
use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Interfaces\ConditionHandlerInterface;
use App\Request\Dto\FilterDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ConditionFactory
{
    /**
     * @var ConditionHandlerInterface[] $handlers
     **/
    private array $handlers = [];

    public function __construct(
        private EntityManagerInterface $manager,
        #[TaggedIterator('query.condition_handler')] iterable $handlers,
    )
    {
        foreach ($handlers as $handler) {
            $this->handlers[$handler->getName()] = $handler;
        }
    }

    public function create(
        string $entityClass,
        FilterDto $filterDto,
    ): AbstractCondition {
        if (!isset($this->handlers[$filterDto->type])) {
            throw new WrongFilterTypeException("filter with type $filterDto->type not support");
        }

        $metadata = $this->manager->getClassMetadata($entityClass);

        return $this->handlers[$filterDto->type]->create(
            $metadata, $filterDto->field, $filterDto->value, $filterDto->options
        );
    }
}