<?php

namespace App\Repositories\data;

use App\Models\Plants;
use App\Repositories\Contracts\PlantsRepository;

class PlantsRepositoryData implements PlantsRepository
{
    /**
     * Get all plants
     *
     * @return mixed
     */
    public function all()
    {
        return Plants::all();
    }

    /**
     * Get plant by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlug(string $slug)
    {
        return Plants::where('slug', $slug)->first();
    }

    /**
     * Create a new plant
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Plants::create($data);
    }

    /**
     * Update a plant
     * 
     * @param array $data
     * @return mixed
     */
    public function update($plant, array $data)
    {
        $plant->update($data);
        return $plant;
    }

    /**
     * Delete a plant
     *
     * @param string $slug
     * @return mixed
     */
    public function delete(string $slug)
    {
        return Plants::where('slug', $slug)->delete();
    }
}
