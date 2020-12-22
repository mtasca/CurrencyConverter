<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Foundation\Entity;

interface EntityIdInterface
{
    public function getId();
    public function getType() : string;
    public function equals(EntityIdInterface $entity_id) : bool;
}
