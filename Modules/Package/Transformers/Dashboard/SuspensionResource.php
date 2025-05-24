<?php

namespace Modules\Package\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SuspensionResource extends JsonResource
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
            "id"            => $this->id,
            "package"       => $this->subscription->package->title,
            "user"          => $this->subscription->user->name,
            "start_at"      => $this->start_at,
            "end_at"        => $this->end_at,
            "created_at"    => $this->created_at->format("d-m-Y H:i a"),

        ];
    }
}
