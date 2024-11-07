<?php

namespace Core\Db;

use DateTime;
use Exception;

trait DeleteTime
{

    private ?string $deleted_at;

    /**
     * @return DateTime
     */
    public function getDeletedAt(): DateTime
    {
        try {
            return new DateTime($this->deleted_at);
        } catch (Exception $e) {
            return new DateTime();
        }
    }

    /**
     * @param DateTime $deleted_at
     */
    public function setDeletedAt(DateTime $deleted_at): void
    {
        $this->deleted_at = $deleted_at->format('Y-m-d H:i:s');
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }
}