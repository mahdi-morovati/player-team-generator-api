<?php


namespace App\Services;


class CommonService
{
    public function pagination($model, $perPage = 15, $with = [])
    {
        if (!$with)
            return $model::latest()->paginate((integer)$perPage);

        return $model::latest()->with($with)->paginate((integer)$perPage);
    }
}
