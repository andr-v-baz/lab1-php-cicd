<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original' => $this->original,
            'translation' => $this->translation,

            'language' => $this->whenLoaded(
                'language',
                fn () => [
                    'id' => $this->language->id,
                    'name' => $this->language->name,
                    'code' => $this->language->code,
                ]
            ),

            'translations' => $this->whenLoaded(
                'translations',
                fn () => $this->translations->map(
                    fn ($translation) => [
                        'id' => $translation->id,
                        'translated_text' => $translation->translated_text,
                        'language' => $translation->language
                            ? [
                                'id' => $translation->language->id,
                                'name' => $translation->language->name,
                                'code' => $translation->language->code,
                            ]
                            : null,
                    ]
                )
            ),

            'owner' => $this->whenLoaded(
                'user',
                fn () => $this->user
                    ? [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ]
                    : null
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
