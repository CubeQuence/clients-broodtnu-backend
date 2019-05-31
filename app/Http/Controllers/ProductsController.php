<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller {

    /**
     * Show all products
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, HttpStatusCodes::SUCCESS_OK);
    }

    /**
     * View product (multiple can be requested with comma's)
     *
     * @param string
     *
     * @return JsonResponse
     */
    public function show($id) {
        $ids = array_map('intval', explode(',', $id));

        return response()->json(
            Product::findOrFail($ids),
            HttpStatusCodes::SUCCESS_OK
        );
    }

    public function create($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    /**
     * Delete product
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function delete($id) {
        // TODO: add access_level system

        Product::findOrFail($id)->delete();

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }
}
