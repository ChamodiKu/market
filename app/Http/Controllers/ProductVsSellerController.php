<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVsSellerRequest;
use App\Services\ProductVsSellerService;


class ProductVsSellerController extends Controller
{
    private $productVsSellerService;

    public function __construct()
    {
        $this->productVsSellerService = new ProductVsSellerService();
    }

    /**
     * {@inheritdoc}
     */
    public function assign(ProductVsSellerRequest $request)
    {
        return $this->productVsSellerService->assign($request);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ProductVsSellerRequest $request)
    {
        return $this->productVsSellerService->update($request);
    }

    /**
     * {@inheritdoc}
     */
    public function unassign(ProductVsSellerRequest $request)
    {
        return $this->productVsSellerService->unassign($request);
    }

    /**
     * {@inheritdoc}
     */
    public function viewAllProductVsSellerAssignments()
    {
        return $this->productVsSellerService->viewAllProductVsSellerAssignments();
    }

    /**
     * {@inheritdoc}
     */
    public function viewProductBySeller($id)
    {
        return $this->productVsSellerService->viewProductBySeller($id);
    }

    /**
     * {@inheritdoc}
     */
    public function viewSellerByProduct($id)
    {
        return $this->productVsSellerService->viewSellerByProduct($id);
    }

}
