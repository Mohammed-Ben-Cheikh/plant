<?php

namespace App\Http\Controllers;

use App\Models\Plants;
use App\Http\Requests\StorePlantsRequest;
use App\Repositories\Contracts\PlantsRepository;
use App\Traits\HttpResponses;

class PlantsController extends Controller
{
    use HttpResponses;
    protected $plantsRepository;

    public function __construct(PlantsRepository $plantsRepository)
    {
        $this->plantsRepository = $plantsRepository;
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plants = $this->plantsRepository->all();
        return $this->success(['Plants' => $plants], 'Plants retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlantsRequest $request)
    {
        $data = $request->validated();
        $plant = $this->plantsRepository->create($data);
        return $this->success(['Plant' => $plant], 'Plant created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $plant = $this->plantsRepository->findBySlug($slug);
        if (!$plant) {
            return $this->error(null, 'Plant not found', 404);
        }
        return $this->success(['Plant' => $plant], 'Plant retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePlantsRequest $request, $slug)
    {
        $data = $request->validated();
        $plant = $this->plantsRepository->findBySlug($slug);
        if (!$plant) {
            return $this->error(null, 'Plant not found', 404);
        }
        $this->plantsRepository->update($plant, $data);
        return $this->success(['Plant' => $plant ], 'Plant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $this->plantsRepository->delete($slug);
        return $this->success(null, 'Plant deleted successfully');
    }
}
