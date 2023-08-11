<?php

namespace App\Tests\Traits;

use Symfony\Component\Uid\Uuid;

trait EntityIdSetterTrait
{
    public function setId(object $subject, int|Uuid $id): object
    {
        $setter = (function ($id) {
            $this->id = $id;
            return $this;
        })(...);

        $setId = \Closure::bind($setter, $subject, get_class($subject));

        return $setId($id);
    }
}
