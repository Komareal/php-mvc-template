<?php

namespace Core\Db;

use DateTime;
use Exception;

trait Datable
{

    private string $created_at;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        try {
            return new DateTime($this->created_at);
        } catch (Exception $e) {
            return new DateTime();
        }
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }

}