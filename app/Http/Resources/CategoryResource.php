<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new CategoryResourceCollection($resource), function ($collection) {
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
            'category'=>$this->category,
            'category_url'=>url("/category/$this->id"),
            'articles_count' => ArticleResource::collection($this->articles)->count(),  //分类文章数
            'articles_url' => ArticleResource::collection($this->articles)->hide(['content']),  //文章链接
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
