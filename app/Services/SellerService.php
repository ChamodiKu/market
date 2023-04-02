<?php

/**
 * {@inheritdoc}
 */

namespace App\Services;

use App\Interfaces\SellerInterface;
use App\Http\Requests\SellerRequest;
use App\Models;
use App\Services\Files\FileOperationService;
use App\Services\Files\FilesService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class SellerService implements SellerInterface {

    private $sellers;
    /**
     * @var FilesService
     */
    private $fileservice;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $apitoken;
    /**
     * @var mixed
     */
    private $channel_name;
    /**
     * @var FileOperationService
     */
    private $fileOpService;
    /**
     * @var array
     */
    private $fullResponse;

    /**
     * {@inheritdoc}
     */
    public function __construct() {
        $this->fileservice = new FilesService();
//        $this->apitoken = config('services.fileapi.file_token');
        $this->channel_name = config::get("services.fileapi.file_channel");

        // call file operations as a single service
        $this->fileOpService = new FileOperationService();
    }

    public function create(SellerRequest $request)
    {
        DB::beginTransaction();
        try {

            // validate email already exists
            $sellerExist = Seller::where('email', '=', $request->email)
                ->first();
            if ($sellerExist) {
                throw new Exception("seller_already_exists", getStatusCodes('EXCEPTION'));
            }

            $seller = new Seller();
            $seller->seller_name = $request->seller_name;
            $seller->email = $request->email;
            $seller->password = bcrypt($request->password);
            $seller->active_status = 1;
            $seller->email_verified_at = now();// this need to update to api id
            $seller->created_at = now();
            $seller->save();
            DB::commit();

            logActivity('seller_create_success',1);

            return response()->json([
                'status' => 'success',
                'message' => 'seller_create_success',
                'data' => $seller
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity('seller_create_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'seller_create_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function update(SellerRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $seller = Seller::where('id', '=', $id)->first();

            // validate is available fo edit
            if (!$seller) {
                throw new Exception("seller_id_not_available", getStatusCodes('EXCEPTION'));
            }

            $seller->seller_name = $request->seller_name;
            $seller->email = $request->email;
            $seller->password = bcrypt($request->password);
            $seller->email_verified_at = now();// this need to update to api
            $seller->save();
            DB::commit();

            logActivity('seller_update_success',1);

            return response()->json([
                'status' => 'success',
                'message' => 'seller_update_success',
                'data' => $seller
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity('seller_update_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'seller_update_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $delSeller = Seller::where('id', '=', $id)->first();

            $delData = Seller::select(['id'])
                ->where ('id', '=', $id)
                ->get();//

            if (!$delSeller) {
                throw new Exception("seller_with_id".$id."_not_available", getStatusCodes('EXCEPTION'));
            }

            $delSeller->delete();

            DB::commit();

            logActivity("Seller_with_id".$id."_Deleted");

            return response()->json([
                'status' => 'success',
                'message' => 'Seller_delete_ok'
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function viewSellers($id=null)
    {
        try{
            // for all Sellers
            if($id == null) {
                $this->sellers = null;
                $this->sellers = Seller::select(['id','product_name','price','image','stock', 'description'])
                    -> get();//

                // Call to image api and get the image path using the token
                $this->fullResponse = [];
                foreach ($this->sellers as $seller) {
                    $imagePath = null;
                    try{
                        $fileData=json_decode($this->fileOpService->readFile($seller->image,'VIEW'));
                        $imagePath = $fileData->data->file_path;
                    } catch(Exception $exception){

                    } finally {
                        $this->fullResponse[] = array_merge($seller->toArray(), ['image ' => $imagePath]);
                    }
                }

                // for single product
            } else {
                $this->sellers = null;
                $this->sellers = Seller::select(['id','seller_name', 'email'])
                    -> where ('id', '=', $id)
                    -> get();//
            }

            if (sizeof($this->sellers) == 0) {
                logActivity('no_records_available',1);
                // if no account send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'success',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity("view_seller_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'view_seller_ok',
                'data' => $this->fullResponse
            ]);
        } catch (Exception $exception) {
            logActivity('view_seller_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }
}

