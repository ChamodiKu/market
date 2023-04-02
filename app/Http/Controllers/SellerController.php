<?php

namespace App\Http\Controllers;
use App\Services\SellerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerRequest;
use App\Services\ProductService;


class SellerController extends Controller
{
    private $sellerService;

    public function __construct()
    {
        $this->sellerService = new SellerService();
    }

    /**
     * {@inheritdoc}
     */
    public function create(SellerRequest $request)
    {
        return $this->sellerService->create($request);
    }

    /**
     * {@inheritdoc}
     */
    public function update(SellerRequest $request, $id)
    {
        return $this->sellerService->update($request, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return $this->sellerService->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function viewSellerById($id)
    {
        return $this->sellerService->viewSellers($id);
    }

    /**
     * {@inheritdoc}
     */
    public function viewAllSellers()
    {
        return $this->sellerService->viewSellers();
    }

}
