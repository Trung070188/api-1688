<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchKeywordResource;
use App\Repositories\ProductDetail\ProductDetailRepository;
use App\Repositories\SearchKeyword\SearchKeywordRepository;
use Illuminate\Http\Request;

class SearchKeywordController extends Controller
{

    private $searchKeywordRepository;
    private $productDetailRepository;

    public function __construct(SearchKeywordRepository $searchKeywordRepository, ProductDetailRepository $productDetailRepository)
    {
        $this->searchKeywordRepository = $searchKeywordRepository;
        $this->productDetailRepository = $productDetailRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyWord = $request->keyword;
        $pageNum = 1;
        $data = $this->searchKeywordRepository->ApiSearchKeyword1688($keyWord, $pageNum);

        return SearchKeywordResource::collection($data['result']['result']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $this->productDetailRepository->getProductDetail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
