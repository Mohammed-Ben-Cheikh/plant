<?php

namespace App\Repositories\data;

use App\Models\Categories;
use App\Traits\HttpResponses;
use App\Repositories\Contracts\CategoriesRepository;

class CategoriesRepositoryData implements CategoriesRepository
{
    use HttpResponses;
    /**
     * Get all categories
     *
     * @return mixed
     */
    public function all()
    {
        return Categories::all();
    }

    /**
     * Get category by ID
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlug(string $slug)
    {
        return Categories::where('slug', $slug)->first();
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Categories::create($data);
    }

    /**
     * Update a category
     * @param array $data
     * @return mixed
     */
    public function update($category, array $data)
    {
        $category->update($data);
        return $category;
    }

    /**
     * Delete a category
     *
     * @param string $slug
     * @return mixed
     */
    public function delete(string $slug)
    {
        return Categories::where('slug', $slug)->delete();
    }
}