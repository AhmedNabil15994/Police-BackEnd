<?php

namespace Modules\Catalog\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Advertising\Repositories\WebService\AdvertisingRepository as Advertising;
use Modules\Advertising\Transformers\WebService\AdvertisingGroupResource;
use Modules\Catalog\Transformers\WebService\CategoryResourceV2;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Catalog\Transformers\WebService\CategoryResource;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Transformers\WebService\SimpleProductResource;
use Modules\Slider\Repositories\WebService\SliderRepository as Slider;
use Modules\Slider\Transformers\WebService\SliderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class CatalogController extends WebServiceController
{
    protected $catalog;
    protected $slider;
    protected $advert;

    function __construct(Catalog $catalog, Slider $slider, Advertising $advert)
    {
        $this->catalog = $catalog;
        $this->slider = $slider;
        $this->advert = $advert;
    }

    public function getHomeData(Request $request): JsonResponse
    {
        // Get Slider Data
        $slider = $this->slider->getRandomPerRequest();
        $result['slider'] = SliderResource::collection($slider);

        /*// Get Featured Products
        $newData = $this->catalog->getFeaturedProducts($request);
        $result['featured_products'] = ProductResource::collection($newData);

        // Get Offers Products
        $bundleOffers = $this->catalog->getOffersData($request);
        $result['offers_products'] = ProductResource::collection($bundleOffers);*/

        // Get By (Show In Home) Latest 10 Categories
        $categories = $this->catalog->getByShowInHomeLatestTenCategories($request);
        $result['categories'] = CategoryResource::collection($categories);

        $adverts = $this->advert->getAdvertGroups();
        $result['advertsGroups'] = AdvertisingGroupResource::collection($adverts);

        return $this->response($result);
    }

    public function getCategoriesTreeWithProducts(Request $request)
    {
        $categories = $this->catalog->getCategoriesTreeWithProducts($request);
        return $this->response(CategoryResource::collection($categories));
    }

    public function getAllProductsByBranch(Request $request)
    {
        $categories = $this->catalog->getProductsByCategory($request);
        return $this->response(CategoryResource::collection($categories));

        /*$products = $this->catalog->getAllProductsByBranch($request);
        return ProductResource::collection($products);*/

        ### automatically append query string to pagination links
        /*$querystringArray = [];
        $querystringArray['search'] = $request->search;
        $querystringArray['category_id'] = $request->category_id;
        $querystringArray['sub_category_id'] = $request->sub_category_id;
        $querystringArray['low_price'] = $request->low_price;
        $querystringArray['high_price'] = $request->high_price;
        $products->appends($querystringArray);*/
    }

    public function searchProducts(Request $request)
    {
        $products = $this->catalog->getAllProductsByBranch($request);
        ### automatically append query string to pagination links
        $querystringArray = [];
        $querystringArray['search'] = $request->search;
        $querystringArray['branch_id'] = $request->branch_id;
        $products->appends($querystringArray);
        $result = ProductResource::collection($products);
        return $this->responsePagination($result);
    }

    public function getAllProductsByBranchV2(Request $request)
    {
        $categories = $this->catalog->getProductsByCategory($request);
        $categories = $categories->map(function ($item) {
            return $item;
        })->reject(function ($item) {
            return count($item->products) == 0;
        });
        return $this->response(CategoryResourceV2::collection($categories));
    }
    public function getOffersProducts(Request $request)
    {
        $products = $this->catalog->getOffersProducts($request);
        ### automatically append query string to pagination links
        $querystringArray = [];
        $querystringArray['search'] = $request->search;
        $querystringArray['branch_id'] = $request->branch_id;
        $products->appends($querystringArray);
        $result = ProductResource::collection($products);
        return $this->responsePagination($result);
    }

    public function getProductDetails(Request $request, $id): JsonResponse
    {
        $product = $this->catalog->getProductDetails($request, $id);
        if ($product) {
            $result = [
                'product' => new ProductResource($product),
                'related_products' => ProductResource::collection($this->catalog->relatedProducts($product)),
            ];
            return $this->response($result);
        } else
            return $this->response(null);
    }

    public function getProductsByCategoryWithoutAddons(Request $request)
    {
        $products = $this->catalog->getProductsByCategoryWithoutAddons($request, config('setting.products.complete_your_meal'));
        $result = SimpleProductResource::collection($products);
        return $this->response($result);
    }

}
