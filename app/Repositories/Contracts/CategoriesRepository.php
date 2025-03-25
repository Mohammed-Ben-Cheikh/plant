<?php

namespace App\Repositories\Contracts;

interface CategoriesRepository
{
    /**
     * Get all categories
     *
     * @return mixed
     */
    public function all();

    /**
     * Get category by ID
     *
string $slug        
     * @return mixed
     */
    public function findBySlug(string $slug);

    /**
     * Create a new category
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a category
     * 
     * @param array $data
     * @return mixed
     */
    public function update($category, array $data);

    /**
     * Delete a category
     *
     * @param string $slug
     * @return mixed
     */
    public function delete(string $slug);
}
