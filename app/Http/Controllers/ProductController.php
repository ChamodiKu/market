<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;


class ProductController extends Controller
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * {@inheritdoc}
     */
    public function create(ProductRequest $request)
    {
        return $this->productService->create($request);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ProductRequest $request, $id)
    {
        return $this->productService->update($request, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return $this->productService->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function viewProductById($id)
    {
        return $this->productService->viewProducts($id);
    }

    /**
     * {@inheritdoc}
     */
    public function viewAllProducts()
    {
        return $this->productService->viewProducts();
    }

}
