<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new UserResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    /**
     * @var array
     */
    protected $hideFields = [];

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
            'id' => $this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile' => $this->mobile,
            'description' => $this->description,
            'last_login_ip' => $this->last_login_ip,
            'articles_count' => ArticleResource::collection($this->articles)->count(),
            'articles' => ArticleResource::collection($this->articles)->hide([
                'content',
                'footprints',
                'created_at',
                'updated_at',
                'deleted_at'
            ]),
            'footprints_count' => FootprintResource::collection($this->footprints)->count(),
            'footprints' => FootprintResource::collection($this->footprints),
            'user_url' => url("/user/$this->id"),
            'avatar_url' => $this->avatar_url,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'deleted_at' => $this->deleted_at?strtotime($this->deleted_at):null
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
