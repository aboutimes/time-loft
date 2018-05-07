<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new ArticleResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    /**
     * @var array
     */
    protected $hidetFields = [];
    /**
     * @var array
     */
    protected $showFields = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'title' => $this->title,
            'author' => $this->author??$this->user->name,//作者未填写则默认为用户名
            'is_reprint' => $this->is_reprint,
            'reprint_url' => $this->reprint??url("/user/$this->id"),
            'content' => $this->content,
            'category' => $this->category->category,
            'tag' => $this->tag->tag,
            'footprints_count' => FootprintResource::collection($this->footprints)->count(),
            'footprints' => FootprintResource::collection($this->footprints),
            'read_number' => $this->read_number,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'is_top' => $this->is_top,
            'article_url' => url("/article/$this->id"),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ]);
    }
    /**
     * Set the keys that are supposed to be filtered out.
     *
     * @param array $fields
     * @return $this
     */
    public function hide(array $fields)
    {
        $this->hideFields = $fields;
        return $this;
    }
    /**
     * Set the keys that are supposed to be filtered out.
     *
     * @param array $fields
     * @return $this
     */
    public function show(array $fields)
    {
        $this->showFields = $fields;
        return $this;
    }

    /**
     * Remove the filtered keys.
     *
     * @param $array
     * @return array
     */
    protected function filterFields($array)
    {
        if (!empty($this->showFields))
        {
            return collect($array)->only($this->showFields)->toArray();
        }
        return collect($array)->forget($this->hideFields)->toArray();
    }
}
