<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchKeywordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return[
            'offerId' => $this['offerId'],
            'subject' => $this['subject'],
            'offerImage' => $this['offerImage'],
            'offerPrice' => $this['offerPrice']
        ];
    }
}
