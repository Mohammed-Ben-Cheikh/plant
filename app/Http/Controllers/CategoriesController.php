<?php

namespace App\Http\Controllers;

use App\Traits\Url;
use App\Models\Categories;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreCategoriesRequest;
use App\Repositories\Contracts\CategoriesRepository;

class CategoriesController extends Controller
{
    use HttpResponses;
    protected $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function index()
    {
        $categories = $this->categoriesRepository->all();
        return $this->success(['Categories' => $categories], 'Categories retrieved successfully',200);
    }

    public function store(StoreCategoriesRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoriesRepository->create($data);
        return $this->success(['Category' => $category], 'Category created successfully', 201);
    }

    public function show($slug)
    {
        $category = $this->categoriesRepository->findBySlug($slug);
        if (!$category) {
            return $this->error(null, 'Category not found', 404);
        }
        return $this->success(['Category' => $category], 'Category retrieved successfully');
    }

    public function update(StoreCategoriesRequest $request, $slug)
    {
        $data = $request->validated();
        $category = $this->categoriesRepository->findBySlug($slug);
        if (!$category) {
            return $this->error(null, 'Category not found', 404);
        }
        $this->categoriesRepository->update($category, $data);
        return $this->success(['Category' => $category], 'Category updated successfully');
    }

    public function destroy($slug)
    {
        $this->categoriesRepository->delete($slug);
        return $this->success(null, 'Category deleted successfully');
    }
}

