<?php

declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Domain\Foundation\Entity\Entity;
use CurrencyConverter\Domain\Model\EntityType;

class Currency extends Entity
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var CurrencyIsoCode
     */
    private $iso_code;

    /**
     * @var int
     */
    private $iso_number;


    /**
     * Currency constructor.
     * @param CurrencyId $id
     * @param string $name
     * @param string $description
     * @param CurrencyIsoCode $iso_code
     * @param int $iso_number
     */
    public function __construct(CurrencyId $id, string $name, string $description, CurrencyIsoCode $iso_code, int $iso_number)
    {
        $this->name = $name;
        $this->description = $description;
        $this->iso_code = $iso_code;
        $this->iso_number = $iso_number;

        parent::__construct($id, $this->toArray());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return CurrencyIsoCode
     */
    public function getIsoCode(): CurrencyIsoCode
    {
        return $this->iso_code;
    }

    /**
     * @param CurrencyIsoCode $iso_code
     */
    public function setCode(CurrencyIsoCode $iso_code): void
    {
        $this->iso_code = $iso_code;
    }

    /**
     * @return int
     */
    public function getIsoNumber(): int
    {
        return $this->iso_number;
    }

    /**
     * @param int $iso_number
     */
    public function setIsoNumber(int $iso_number): void
    {
        $this->iso_number = $iso_number;
    }

    public function getType(): string
    {
        return EntityType::CURRENCY;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'iso_code' => $this->iso_code->getValue(),
            'iso_number' => $this->iso_number,
        ];
    }
}