<?php

namespace Modules\Package\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class PrintSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data["created_at"] = $this->created_at->format("d-m-Y");
        return $data;
    }
}
