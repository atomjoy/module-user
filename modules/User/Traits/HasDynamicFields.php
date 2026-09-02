<?php

namespace Mod\User\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasDynamicFields
{
    public function addFillableFields(array $fields): self
    {
        /** @var Model $this */
        return $this->mergeFillable($fields);
    }

    public function addHiddenFields(array $fields): self
    {
        /** @var Model $this */
        return $this->makeHidden($fields);
    }
}
