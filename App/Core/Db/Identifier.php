<?php

namespace Core\Db;

trait Identifier
{

    /**
     * ID from the db
     * @var int|null
     */
    protected ?int $id = null;

    public function __clone()
    {
        $this->id = -1;
    }

    final public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}