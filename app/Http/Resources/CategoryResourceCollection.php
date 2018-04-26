<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryResourceCollection extends ResourceCollection
{
    /**
     * @var array
     */
    protected $hideFields = [];
    /**
     * @var array
     */
    protected $showFields = [];
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->processCollection($request);
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
     * Send fields to hide to UsersResource while processing the collection.
     *
     * @param $request
     * @return array
     */
    protected function processCollection($request)
    {
        if (!empty($this->showFields))
        {
            return $this->collection->map(function (CategoryResource $resource) use ($request) {
                return $resource->show($this->showFields)->toArray($request);
            })->all();
        }
        return $this->collection->map(function (CategoryResource $resource) use ($request) {
            return $resource->hide($this->hideFields)->toArray($request);
        })->all();
    }
}
