<?php

namespace Modules\Package\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            "id"    => $this->id ,
            "title" => $this->title ,
            "description" => $this->description ,
            "status"    => $this->status ,
            "is_free"    => $this->is_free ,
            "photo"    => $this->photo ,
            "price"    => $this->price ,
            "country_id" => $this->country_id,
            "created_at"=> $this->created_at->format("d-m-Y H:i a") ,
            "deleted_at" => $this->deleted_at

        ];
    }
}
