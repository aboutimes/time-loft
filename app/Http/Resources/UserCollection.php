<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'first'=> 'http://example.com/pagination?page=1',
                'last'=> 'http://example.com/pagination?page=1',
                'prev'=> null,
                'next'=> null
            ],
            'meta' => [
                'current_page' =>  1,
                'from' =>  1,
                'last_page' =>  1,
                'path' =>  'http => //example.com/pagination',
                'per_page' =>  15,
                'to' =>  10,
                'total' =>  10
            ]
        ];
    }
}
