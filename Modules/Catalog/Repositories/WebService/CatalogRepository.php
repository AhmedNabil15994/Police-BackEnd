<?php

namespace Modules\Catalog\Repositories\WebService;

use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\VendorProduct;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Variation\Entities\ProductVariant;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Traits\VendorTrait;

class CatalogRepository
{
    use VendorTrait, CatalogTrait;

    protected $category;
    protected $product;
    protected $vendor;
    protected $prd;
    protected $prdVariant;
    protected $defaultVendor;

    function __construct(VendorProduct $product, Product $prd, Category $category, Vendor $vendor, ProductVariant $prdVariant)
    {
        $this->category = $category;
        $this->product = $product;
        $this->vendor = $vendor;
        $this->prd = $prd;
        $this->prdVariant = $prdVariant;

        $this->defaultVendor = $this->getSingleVendor() ?? null;
    }

    public function getCategoriesTreeWithProducts($request)
    {
        $categories = $this->buildCategoriesTree($request, 'categories');
        return $categories->orderBy('sort', 'ASC')->get();
    }

    public function getAllProductsByBranch($request)
    {
        $vendorId = $request->branch_id ?? ($this->defaultVendor->id ?? null);
        $category_array = [];
        $products = $this->prd->with(['variants' => function ($q) {
            $q->with(['offer' => function ($q) {
                $q->active()->unexpired()->started();
            }]);
        }])->active();

        if (!is_null($vendorId)) {
            $products = $this->defaultVendorCondition($products, $vendorId);
        }

        if (isset($request['category_id']) && $request['category_id'] == 'latest_products') {
            $products = $products->doesnthave('offer');
        } elseif (isset($request['category_id']) && $request['category_id'] == 'offers_products') {
            $products = $products->whereHas('offer', function ($query) {
                $query->active()->unexpired()->started();
            });
        } else {

            if (isset($request['category_id']) && !empty($request['category_id']))
                $category_array[] = $request['category_id'];

            if (isset($request['sub_category_id']) && !empty($request['sub_category_id'])) {
                $category_array[] = $request['sub_category_id'];
            }

            $products = $products->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
            ]);
        }

        if (count($category_array) > 0) {
            $products->whereHas('categories', function ($query) use ($category_array) {
                $query->whereIn('product_categories.category_id', $category_array);
            });
        }

        if ($request['low_price'] && $request['high_price']) {
            $products->whereBetween('price', [$request['low_price'], $request['high_price']]);
        }

        if ($request['search']) {
            $products = $this->productSearch($products, $request);
        }

        if (config('setting.products.toggle_ordering_time') == 1) {
            $products = $this->checkProductAvailabilityQuery($products);
        }

        return $products->orderBy('id', 'DESC')->paginate(24);
    }

    public function getOffersProducts($request)
    {
        $vendorId = $request->branch_id ?? ($this->defaultVendor->id ?? null);
        $product = $this->prd->active();

        if (!is_null($vendorId)) {
            $product = $this->defaultVendorCondition($product, $vendorId);
        }

        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        $product = $product->whereHas('offer', function ($query) {
            $query->active()->unexpired()->started();
        });

        if (config('setting.products.toggle_ordering_time') == 1) {
            $product = $this->checkProductAvailabilityQuery($product);
        }

        return $product->orderBy('id', 'DESC')->paginate(24);
    }

    public function getProductDetails($request, $id)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $this->defaultVendorCondition($product, $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, $request);

        if (config('setting.products.toggle_ordering_time') == 1) {
            $productData = ['key' => 'id', 'value' => $id];
            $product = $this->checkProductAvailabilityQuery($product, $productData);
        }

        return $product->find($id);
    }

    public function getFeaturedProducts($request)
    {
        $items = $this->prd->doesnthave('offer')->active();
        $items = $items->where('featured', '1');

        if (!is_null($this->defaultVendor)) {
            $items = $this->defaultVendorCondition($items, $this->defaultVendor->id);
        }

        $items = $this->returnProductRelations($items, $request);

        if ($request['search']) {
            $items = $this->productSearch($items, $request);
        }

        $items = $items->orderBy('id', 'desc')->take(10)->get();
        return $items;
    }

    public function getOffersData($request)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $this->defaultVendorCondition($product, $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        $product = $product->whereHas('offer', function ($query) {
            $query->active()->unexpired()->started();
        });

        return $product->take(10)->get();
    }

    public function findOneProduct($id)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $this->defaultVendorCondition($product, $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, null);

        if (config('setting.products.toggle_ordering_time') == 1) {
            $productData = ['key' => 'id', 'value' => $id];
            $product = $this->checkProductAvailabilityQuery($product, $productData);
        }

        return $product->find($id);
    }

    public function findOneProductVariant($id)
    {
        $product = $this->prdVariant->with([
            'product',
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues' => function ($q) {
                $q->with(['optionValue', 'productOption' => function ($q) {
                    $q->with('option');
                }]);
            }
        ]);

        if (!is_null($this->defaultVendor)) {
            $product = $product->whereHas('product', function ($query) {
                $this->defaultVendorCondition($query, $this->defaultVendor->id);
                if (config('setting.products.toggle_ordering_time') == 1) {
                    $this->checkProductAvailabilityQuery($query);
                }
            });
        }

        return $product->active()->find($id);
    }

    public function getAllSubCategoriesByParent($id)
    {
        return $this->category->where('category_id', $id)->get();
    }

    public function getByShowInHomeLatestTenCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        return $categories->orderBy('sort', 'ASC')->get();
    }

    public function buildCategoriesTree($request, $flag = 'home')
    {
        $vendorId = $request->branch_id ?? ($this->defaultVendor->id ?? null);
        $categories = $this->category->active();
        /*->with('childrenRecursive')->withCount(['products' => function ($q) use ($request, $vendorId) {
            $q->active();

            if (!is_null($vendorId)) {
                $this->defaultVendorCondition($q, $vendorId);
            }
        }]);*/
        /*->where('id', '<>', '1');*/

        if ($flag == 'home')
            $categories = $categories->where('show_in_home', '1');

        ### Get Main Category Products ###
        $categories = $categories->mainCategories();

        if ($flag == 'categories') {
            ### Start - Check If Category Has Products
            $categories = $categories->whereHas(
                'products',
                function ($query) use ($request, $vendorId) {
                    $query->active();
                    if (!is_null($vendorId)) {
                        $this->defaultVendorCondition($query, $vendorId);
                    }

                    if (config('setting.products.toggle_ordering_time') == 1) {
                        $query = $this->checkProductAvailabilityQuery($query);
                    }
                },
            );
            ### End - Check If Category Has Products

            $categories = $categories->with([
                'products' => function ($query) use ($request, $vendorId) {
                    $query->active();
                    $query = $this->returnProductRelations($query, $request);

                    if (!is_null($vendorId)) {
                        $this->defaultVendorCondition($query, $vendorId);
                    }

                    if (config('setting.products.toggle_ordering_time') == 1) {
                        $query = $this->checkProductAvailabilityQuery($query);
                    }

                    $query->orderBy('id', 'DESC');
                },
            ]);
        }

        return $categories;
    }

    public function getProductsByCategory($request)
    {
        $vendorId = $request->branch_id ?? ($this->defaultVendor->id ?? null);
        $categories = $this->category->active();/*->with('childrenRecursive')->withCount(['products' => function ($q) use ($vendorId) {
            $q->active();

            if (!is_null($vendorId)) {
                $this->defaultVendorCondition($q, $vendorId);
            }
        }]);*/

        if (!empty($request->category_id))
            $categories = $categories->where('id', $request->category_id);

        ### Get Main Category Products ###
        // $categories = $categories->mainCategories();

        ### Start - Check If Category Has Products
        $categories = $categories->whereHas(
            'products',
            function ($query) use ($vendorId) {
                $query->active();
                if (!is_null($vendorId)) {
                    $this->defaultVendorCondition($query, $vendorId);
                }

                if (config('setting.products.toggle_ordering_time') == 1) {
                    $query = $this->checkProductAvailabilityQuery($query);
                }
            },
        );
        ### End - Check If Category Has Products

        $categories = $categories->with([
            'products' => function ($query) use ($request, $vendorId) {
                $query->active();
                $query = $this->returnProductRelations($query, $request);

                if (!is_null($vendorId)) {
                    $this->defaultVendorCondition($query, $vendorId);
                }

                if (config('setting.products.toggle_ordering_time') == 1) {
                    $query = $this->checkProductAvailabilityQuery($query);
                }

                $query->orderBy('id', 'DESC');
            },
        ]);

        return $categories->get();
    }

    public function productSearch($model, $request)
    {
        return $model->whereHas('translations', function ($query) use ($request) {

            $query->where('title', 'like', '%' . $request['search'] . '%');
            $query->orWhere('description', 'like', '%' . $request['search'] . '%');
            $query->orWhere('slug', 'like', '%' . $request['search'] . '%');
        });
    }

    public function returnProductRelations($model, $request)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            'productVendors.states',
            'subCategories',
            'addOns.addOnOptions',
            'variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }

    public function relatedProducts($selectedProduct)
    {
        $relatedCategoriesIds = $selectedProduct->categories()->pluck('product_categories.category_id')->toArray();
        $products = $this->prd->where('id', '<>', $selectedProduct->id)->active();
        $products = $products->whereHas('categories', function ($query) use ($relatedCategoriesIds) {
            $query->whereIn('product_categories.category_id', $relatedCategoriesIds);
        });
        if (config('setting.products.toggle_ordering_time') == 1) {
            $products = $this->checkProductAvailabilityQuery($products);
        }
        return $products->orderBy('id', 'desc')->take(10)->get();
    }

    public function getProductsByCategoryWithoutAddons($request, $categories)
    {
        $vendorId = $request->branch_id ?? ($this->defaultVendor->id ?? null);
        $products = $this->prd->with(['offer' => function ($query) {
            $query->active()->unexpired()->started();
        }])->active();

        if (!is_null($vendorId)) {
            $products = $this->defaultVendorCondition($products, $vendorId);
        }

        if (count($categories) > 0) {
            $products->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('product_categories.category_id', $categories);
            });
        }

        $products = $products->doesntHave('addOns');
        $products = $products->doesntHave('variants');

        if (config('setting.products.toggle_ordering_time') == 1) {
            $products = $this->checkProductAvailabilityQuery($products);
        }

        return $products->orderBy('id', 'DESC')->get();
    }
}
