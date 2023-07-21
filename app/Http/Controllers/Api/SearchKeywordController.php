<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchKeywordResource;
use App\Repositories\ProductDetail\ProductDetailRepository;
use App\Repositories\SearchKeyword\SearchKeywordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $pageNum = $request->page ?? 1;
        $entry = $this->searchKeywordRepository->ApiSearchKeyword1688($keyWord, $pageNum);
        if (count($entry['result']) > 2) {
            $result = SearchKeywordResource::collection($entry['result']['result']);
            $data = [
                'data' => $result,
                'productPaginate' => [
                    'currentPage' => $entry['result']['pageInfo']['currentPage'],
                    'lastPage' => $entry['result']['pageInfo']['totalPage']
                ]
            ];
            return response()->json($data, 200);

        }

        else {

            return response()->json(['message' => 'Không tìm thấy sản phẩm']);
        }
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
