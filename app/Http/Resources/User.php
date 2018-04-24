<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile' => $this->mobile,
            'description' => $this->description,
            'last_login_ip' => $this->last_login_ip,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->created_at
        ];
    }
}
