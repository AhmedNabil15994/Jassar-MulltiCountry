<?php

namespace Modules\Apps\Transformers\dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class HeadermenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
         return [
                    'id' => $this->id,
        ];
    }
}
