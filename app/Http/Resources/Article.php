<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Article extends Resource
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
            'title' => $this->title,
            'author' => $this->author??$this->user->name,//作者未填写则默认为用户名
            'content' => $this->content,
            'read_number' => $this->read_number,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'is_top' => $this->is_top,
        ];
    }
}
