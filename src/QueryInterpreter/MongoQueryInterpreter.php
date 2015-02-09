<?php
/**
 * Silktide Nibbler. Copyright 2013-2014 Silktide Ltd. All Rights Reserved.
 */
namespace Silktide\Reposition\QueryInterpreter;

use Silktide\Reposition\Exception\QueryException;
use Silktide\Reposition\Normaliser\NormaliserInterface;
use Silktide\Reposition\Query\Query;
use Silktide\Reposition\Query\FindQuery;

/**
 *
 */
class MongoQueryInterpreter implements QueryInterpreterInterface
{

    /**
     * @var NormaliserInterface
     */
    protected $normaliser;

    /**
     * {@inheritDoc}
     * @throws \Silktide\Reposition\Exception\QueryException
     */
    public function interpret(Query $query)
    {
        switch ($query->getAction()) {
            case Query::ACTION_FIND:
                /** @var FindQuery $query */
                return $this->compileFindQuery($query);
                break;
            default:
                throw new QueryException("Invalid query action: {$query->getAction()}");
        }
    }

    /**
     * @param FindQuery $query
     * @return CompiledQuery
     */
    protected function compileFindQuery(FindQuery $query)
    {
        $calls = [];
        $limit = $query->getLimit();
        if (!empty($limit)) {
            $calls[] = ["limit", [$limit]];
        }
        $sort = $query->getSort();
        if (!empty($sort)) {
            foreach ($sort as $field => $direction) {
                $sort[$field] = ($direction == Query::SORT_ASCENDING)? 1: -1;
            }
            $calls[] = ["sort", [$this->normalise($sort, ["filter" => "keys"])]];
        }

        return new CompiledQuery(
            $query->getTable(),
            "find",
            [
                $this->normalise($query->getFilters())
            ],
            $calls
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setNormaliser(NormaliserInterface $normaliser)
    {
        $this->normaliser = $normaliser;
    }

    /**
     * @param array $data
     * @param array $options
     * @return array
     */
    protected function normalise(array $data, array $options = [])
    {
        if ($this->normaliser instanceof NormaliserInterface) {
            return $this->normaliser->normalise($data, $options);
        }
        return $data;
    }

} 