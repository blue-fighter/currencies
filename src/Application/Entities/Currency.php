<?php

namespace Application\Entities;

use Application\Repositories\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: "currency")]
class Currency
{
    const PIVOT_CURRENCY_CHARACTER_CODE = 'RUR';

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;
    #[ORM\Column(length: 3)]
    private ?string $numericCode;
    #[ORM\Column(length: 3)]
    private ?string $characterCode;
    #[ORM\Column(type: 'integer')]
    private ?int $nominal;
    #[ORM\Column(length: 128)]
    private ?string $name;
    #[ORM\Column(type: 'integer')]
    private ?int $value;
    #[ORM\Column(type: 'date')]
    private ?DateTime $date;

    /**
     * @param string $numericCode
     * @param string $characterCode
     * @param int $nominal
     * @param string $name
     * @param int $value
     */
    public function __construct(
        ?string $numericCode,
        ?string $characterCode,
        ?int $nominal,
        ?string $name,
        ?int $value,
        DateTime $date
    )
    {
        $this->numericCode = $numericCode;
        $this->characterCode = $characterCode;
        $this->nominal = $nominal;
        $this->name = $name;
        $this->value = $value;
        $this->date = $date;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    /**
     * @return string|null
     */
    public function getCharacterCode(): ?string
    {
        return $this->characterCode;
    }

    /**
     * @return int|null
     */
    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return !is_null($this->value) ? $this->value / 10000 : null;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }
}