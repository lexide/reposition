<?php

namespace Lexide\Reposition\QueryBuilder;

use Lexide\Reposition\Metadata\EntityMetadata;

/**
 *
 */
interface QueryBuilderInterface extends TokenSequencerInterface
{

    const PRIMARY_KEY = "id";

    /**
     * @param EntityMetadata $entity
     * @return TokenSequencerInterface
     */
    public function find(EntityMetadata $entity);

    /**
     * @param EntityMetadata $entity
     * @return TokenSequencerInterface
     */
    public function update(EntityMetadata $entity);

    /**
     * @param EntityMetadata $entity
     * @param array $options
     * @return TokenSequencerInterface
     */
    public function save(EntityMetadata $entity, array $options = []);

    /**
     * @param EntityMetadata $entity
     * @return TokenSequencerInterface
     */
    public function delete(EntityMetadata $entity);

    /**
     * @return TokenSequencerInterface
     */
    public function expression();

} 
