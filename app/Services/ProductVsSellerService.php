<?php

/**
 * {@inheritdoc}
 */

namespace App\Services;

use App\Interfaces\ProductVsSellerInterface;
use App\Http\Requests\ProductVsSellerRequest;
use App\Models;
use App\Services\Files\FileOperationService;
use App\Services\Files\FilesService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class ProductVsSellerService implements ProductVsSellerInterface {

    private $productsvssellers;

    public function assign(ProductVsSellerRequest$request)
    {
        DB::beginTransaction();
        try {

            // validate product_vs_seller_assignment already exists
            $assigmentExist = ProductVsSeller::where('product_id', '=', $request->product_id)
                ->where('seller_id', '=', $request->seller_id)
                ->first();
            if ($assigmentExist) {
                throw new Exception("product_vs_seller_assignment_already_exists", getStatusCodes('EXCEPTION'));
            }

            // Check Product exists
            $productExist = Product::where([['id', '=', $request->product_id]])->first();
            if (!$productExist) {
                throw new Exception("product_not_exists", getStatusCodes('EXCEPTION'));
            }

            // Check Seller exists
            $sellerExist = Seller::where([['id', '=', $request->seller_id]])->first();
            if (!$sellerExist) {
                throw new Exception("seller_not_exists", getStatusCodes('EXCEPTION'));
            }

            $productvsseller = new ProductVsSeller();
            $productvsseller->product_id = $request->product_id;
            $productvsseller->seller_id = $request->seller_id;
            $productvsseller->seller_price = $request->seller_price;
            $productvsseller->seller_stock = $request->seller_stock;
            $productvsseller->modified_at = now();// this need to update to api id
            $productvsseller->save();
            DB::commit();

            logActivity('product_vs_seller_assign_success',1);

            return response()->json([
                'status' => 'success',
                'message' => 'product_vs_seller_assign_success',
                'data' => $productvsseller
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity('product_vs_seller_assign_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'product_vs_seller_assign_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function update(ProductVsSellerRequest$request)
    {
        DB::beginTransaction();
        try {

            $productvsseller = ProductVsSeller::where('product_id', '=', $request->product_id)
                ->where('seller_id', '=', $request->seller_id)
                ->first();
            if (!$productvsseller) {
                throw new Exception("product_vs_seller_assignment_not_exists", getStatusCodes('EXCEPTION'));
            }

                $productvsseller->seller_price = $request->seller_price;
                $productvsseller->seller_stock = $request->seller_stock;
                $productvsseller->modified_at = now();// this need to update to api id
                $productvsseller->save();
                DB::commit();

                logActivity('product_vs_seller_update_success',1);

                return response()->json([
                    'status' => 'success',
                    'message' => 'product_vs_seller_update_success',
                    'data' => $productvsseller
                ], getStatusCodes('SUCCESS'));

            } catch (Exception $exception) {
                DB::rollBack();

            logActivity('product_vs_seller_update_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'product_vs_seller_update_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function unassign(ProductVsSellerRequest $request)
    {
        DB::beginTransaction();
        try {

            $delAs = ProductVsSeller::where('product_id', '=', $request->product_id)
                ->where('seller_id', '=', $request->seller_id)
                ->first();

            if (!$delAs) {
                throw new Exception("product_vs_seller_assignment_not_available", getStatusCodes('EXCEPTION'));
            }

            $delAs->delete();

            DB::commit();

            logActivity("product_vs_seller_assignment_not_available");

            return response()->json([
                'status' => 'success',
                'message' => 'product_vs_seller_unassign_ok'
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function viewAllProductVsSellerAssignments()
    {
        try{
            // for all Productvssellers assignments
            $productVsSeller = ProductVsSeller::select('product_id', 'seller_id', 'seller_price', 'seller_stock')
                ->with('product:id,product_name,description')
                ->with('seller:id,seller_name,email')
                ->get();

            if (sizeof($productVsSeller) == 0) {
                logActivity('no_records_available', 1);
                // if no account send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'success',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity("view_all_product_vs_seller_assignments_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'product_vs_seller_assignments_view_ok',
                'data' => $productVsSeller->toArray()
            ]);
            ;
        } catch (Exception $exception) {
            logActivity('product_vs_seller_assignments_view_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function viewProductBySeller($id)
    {
        try{
            $productBySeller = ProductVsSeller::select('product_id', 'seller_id', 'seller_price', 'seller_stock')
                ->with('product:id,product_name,description')
                ->with('seller:id,seller_name,email')
                ->get();

            if (sizeof($productBySeller) == 0) {
                logActivity('no_records_available',1);
                // if no assignments send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'success',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity("view_product_by_seller_view_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'view_product_by_seller_view_ok',
                'data' => $productBySeller
            ]);
        } catch (Exception $exception) {
            logActivity('product_by_seller_view_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function viewSellerByProduct($id)
    {
        try{
            $sellerByProduct = ProductVsSeller::select('product_id', 'seller_id', 'seller_price', 'seller_stock')
                ->with('product:id,product_name,description')
                ->with('seller:id,seller_name,email')
                ->get();

            if (sizeof($sellerByProduct) == 0) {
                logActivity('no_records_available',1);
                // if no assignments send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'success',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity("view_seller_by_product_view_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'view_seller_by_product_view_ok',
                'data' => $sellerByProduct
            ]);
        } catch (Exception $exception) {
            logActivity('seller_by_product_view_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

}

