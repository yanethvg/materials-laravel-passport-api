<?php

namespace App\Http\Resources;

use App\Http\Resources\UnitMeasureResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->measure);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'stock_minim' => $this->stock_minim,
            'is_active' => $this->is_active,	
            'measure' =>  new UnitMeasureResource($this->measure),
            'category' => new CategoryResource($this->category),
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')
        ];
    }
}
