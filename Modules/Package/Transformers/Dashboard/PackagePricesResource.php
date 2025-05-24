<?php

namespace Modules\Package\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagePricesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $package =  new PackageResource($this->package);
        return [
            "id"    => $this->id,
            "key" => "update_{$this->id}",
            "action" => 'update',
            "price" => $this->price,
            "offer_percentage" => $this->offer_percentage,
            "start_offer_date" => $this->start_offer_date,
            "end_offer_date" => $this->end_offer_date,
            "same_pricerenew_times" => $this->same_pricerenew_times,
            "max_puse_days" => $this->max_puse_days,
            "days_count" => $this->days_count,
            'package' => $package ,
            'photo' => $package->photo,
            "subscribe_duration_desc" => [
                'en' => $this->getTranslation('subscribe_duration_desc','en'),
                'ar' => $this->getTranslation('subscribe_duration_desc','ar'),
            ],
            "title" => [
                'en' => $this->getTranslation('title','en'),
                'ar' => $this->getTranslation('title','ar'),
            ],
        ];
    }
}
