<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;

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
        $id = array_map('intval', explode(',', $id));

        return response()->json(
            Product::findOrFail($id),
            HttpStatusCodes::SUCCESS_OK
        );
    }
}
