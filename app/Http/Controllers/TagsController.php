<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use App\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;

class TagsController extends Controller {

    /**
     * Show all tags
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(
            Tag::all(),
            HttpStatusCodes::SUCCESS_OK
        );
    }

    /**
     * View tag (multiple can be requested with comma's)
     *
     * @param string
     *
     * @return JsonResponse
     */
    public function show($id) {
        $ids = array_map('intval', explode(',', $id));

        return response()->json(
            Tag::findOrFail($ids),
            HttpStatusCodes::SUCCESS_OK
        );
    }

    /**
     * View products with the requested tag
     *
     * @param string
     *
     * @return JsonResponse
     */
    public function showProducts($id) {
        $ids = array_map('intval', explode(',', $id));

        return response()->json(
            Product::whereJsonContains('tags', $ids)->get(),
            HttpStatusCodes::SUCCESS_OK
        );
    }
}
