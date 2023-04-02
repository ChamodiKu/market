<?php

/**
 * {@inheritdoc}
 */

namespace App\Services;

use App\Interfaces\ProductInterface;
use App\Http\Requests\ProductRequest;
use App\Models;
use App\Services\Files\FileOperationService;
use App\Services\Files\FilesService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class ProductService implements ProductInterface {

    private $products;
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

    public function create(ProductRequest $request)
    {
        DB::beginTransaction();
        try {

            // validate product_name already exists
            $productNameExist = Product::where('product_name', '=', $request->product_name)
                ->first();
            if ($productNameExist) {
                throw new Exception("product_name_already_exists", getStatusCodes('EXCEPTION'));
            }

            $accessToken = null;

            if($request->hasFile('image')) {
                // Call to file service to upload the file
                $file_name = $request->image->getClientOriginalName();
                $active_status = 1;
                $path_enable = 1; // file path required together with token
                $mimetype = $request->image->getMimeType();
                //https://stackoverflow.com/questions/63348476/how-to-send-received-file-to-external-api-with-laravel

                $options = [

//                    ['name' => 'api_token', 'contents' => $this->apitoken],
                    ['name' => 'file_name', 'contents' => $file_name],
                    ['name' => 'channel_name', 'contents' => $this->channel_name],
                    ['name' => 'active_status', 'contents' => $active_status],
                    ['name' => 'path_enable', 'contents' => $path_enable],
                    [
                        'name' => 'file',
                        'contents' => fopen($request->file('image'), 'r'),
                        'headers' => ['Content-Type' => $mimetype]
                    ],
                ];

                // Extract the token from the response after upload the file.
                $createdData = json_decode($this->fileservice->createFile($options));
                $filePath = $createdData->data->file_path;
                // Extract the token from the response after upload the file.
                $accessToken = $createdData->data->access_token;
            }

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->images_path = $accessToken;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->modified_at = now();// this need to update to api id
            $product->save();
            DB::commit();

            logActivity('product_create_success',1);

            return response()->json([
                'status' => 'success',
                'message' => 'product_create_success',
                'data' => $product
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity('product_create_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'product_create_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $product = Product::where('id', '=', $id)->first();

            // validate token is available fo edit
            if (!$product) {
                throw new Exception("product_id_not_available", getStatusCodes('EXCEPTION'));
            }

            $accessToken = null;

            if($request->hasFile('image')) {
                // Call to file service to upload the file
                $file_name = $request->image->getClientOriginalName();
                $active_status = 1;
                $path_enable = 1; // file path required together with token
                $mimetype = $request->image->getMimeType();
                    //https://stackoverflow.com/questions/63348476/how-to-send-received-file-to-external-api-with-laravel

                    $options = [

//                    ['name' => 'api_token', 'contents' => $this->apitoken],
                        ['name' => 'file_name', 'contents' => $file_name],
                        ['name' => 'channel_name', 'contents' => $this->channel_name],
                        ['name' => 'active_status', 'contents' => $active_status],
                        ['name' => 'path_enable', 'contents' => $path_enable],
                        [
                            'name' => 'file',
                            'contents' => fopen($request->file('image'), 'r'),
                            'headers' => ['Content-Type' => $mimetype]
                        ],
                    ];

                    // Extract the token from the response after upload the file.
                    $createdData = json_decode($this->fileservice->createFile($options));
                    $filePath = $createdData->data->file_path;
                    // Extract the token from the response after upload the file.
                    $accessToken = $createdData->data->access_token;
                }

                $product->product_name = $request->product_name;
                $product->price = $request->price;
                $product->images_path = $accessToken;
                $product->stock = $request->stock;
                $product->description = $request->description;
                $product->modified_at = now();// this need to update to api id
                $product->save();
                DB::commit();

                logActivity('product_update_success',1);

                return response()->json([
                    'status' => 'success',
                    'message' => 'product_update_success',
                    'data' => $product
                ], getStatusCodes('SUCCESS'));

            } catch (Exception $exception) {
                DB::rollBack();

            logActivity('product_update_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'product_update_fail_'.$exception->getMessage(),
            ], getStatusCodes('EXCEPTION'));
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $delProduct = Product::where('id', '=', $id)->first();

            $delData = Product::select(['id','image'])
                ->where ('id', '=', $id)
                ->get();//

            if (!$delProduct) {
                throw new Exception("product_with_id".$id."_not_available", getStatusCodes('EXCEPTION'));
            }

            $delProduct->delete();
            // If symptom create fail delete the file too
            if($delData[0]->image){
                $this->fileOpService->deleteFile($delData[0]->image);
            }

            DB::commit();

            logActivity("Product_with_id".$id."_Deleted");

            return response()->json([
                'status' => 'success',
                'message' => 'Product_delete_ok'
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($exception->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function viewProducts($id=null)
    {
        try{
            // for all Products
            if($id == null) {
                $this->products = null;
                $this->products = Product::select(['id','product_name','price','image','stock', 'description'])
                    -> get();//

                // Call to image api and get the image path using the token
                $this->fullResponse = [];
                foreach ($this->products as $product) {
                    $imagePath = null;
                    try{
                        $fileData=json_decode($this->fileOpService->readFile($product->image,'VIEW'));
                        $imagePath = $fileData->data->file_path;
                    } catch(Exception $exception){

                    } finally {
                        $this->fullResponse[] = array_merge($product->toArray(), ['image ' => $imagePath]);
                    }
                }

                // for single product
            } else {
                $this->products = null;
                $this->products = Product::select(['id','product_name','price','image','stock', 'description'])
                    -> where ('id', '=', $id)
                    -> get();//

                // Call to image api and get the image using the token
                $this->fullResponse = [];
                foreach ($this->products as $product) {
                    $imagePath = null;
                    try{
                        $fileData=json_decode($this->fileOpService->readFile($product->image,'VIEW'));
                        $imagePath = $fileData->data->file_path;
                    } catch(Exception $exception){

                    } finally {
                        $this->fullResponse[] = array_merge($product->toArray(), ['image' => $imagePath]);
                    }
                }
            }

            if (sizeof($this->products) == 0) {
                logActivity('no_records_available',1);
                // if no account send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'success',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity("view_product_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'view_product_ok',
                'data' => $this->fullResponse
            ]);
        } catch (Exception $exception) {
            logActivity('view_product_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }
}

