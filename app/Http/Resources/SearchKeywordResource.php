<?php

namespace App\Http\Resources;

use App\Services\TranslateLanguageByGoogle;
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
        $trans = new TranslateLanguageByGoogle();
        return[
            'offerId' => $this['offerId'],
            'subject' => $trans->translateLanguage($this['subject'],'vi'),
            'offerImage' => $this['offerImage'],
            'offerPrice' => $this['offerPrice']
        ];
    }
}
