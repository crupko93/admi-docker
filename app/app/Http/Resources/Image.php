<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request = null)
    {
        return [
            'id'          => $this->id,
            'path'        => $this->getFullPath(),
            'title'       => $this->title,
            'description' => $this->description,
            'sizes'       => [
                'thumbnail' => $this->getFullPath('thumbnail'),
                'preview'   => $this->getFullPath('preview')
            ]
        ];
    }
}
