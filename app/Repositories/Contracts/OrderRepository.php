<?php

namespace App\Repositories\Contracts;

interface OrderRepository
{
    /**
     * Get all plants
     *
     * @return mixed
     */
    public function all();

    /**
     * Get plant by slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlug(string $slug);

    /**
     * Get plant by user
     *
     * @param int $userId
     * @return mixed
     */
    public function findByUser(int $userId);

    /**
     * Create a new plant
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a plant
     * 
     * @param array $data
     * @return mixed
     */
    public function update($plant, array $data);

    /**
     * Delete a plant
     *
     * @param string $slug
     * @return mixed
     */
    public function delete(string $slug);
}
