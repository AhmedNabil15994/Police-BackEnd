<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Catalog\Entities\Product;
use Illuminate\Support\Arr;
use DB;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\OptionValue;
use Modules\Variation\Entities\ProductVariant;
use Modules\Variation\Entities\ProductVariantValue;
use Modules\Vendor\Traits\VendorTrait;

class ProductRepository
{
    use VendorTrait, CatalogTrait;

    protected $product;
    protected $variantPrd;
    protected $variantPrdValue;
    protected $option;
    protected $optionValue;

    function __construct(Product $product, ProductVariant $variantPrd, ProductVariantValue $variantPrdValue, Option $option, OptionValue $optionValue)
    {
        $this->product = $product;
        $this->variantPrd = $variantPrd;
        $this->variantPrdValue = $variantPrdValue;
        $this->option = $option;
        $this->optionValue = $optionValue;
    }

    public function findBySlug($slug, $checkAvailability = true)
    {
        $product = $this->product->active()
            ->with([
                "productVendors",
                "categories",
                "images",
                "tags",
                "options.option",
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
                'addOns'
            ]);

        if ($checkAvailability == true) {
            $productData = ['key' => 'slug', 'value' => $slug];
            $product = $this->checkProductAvailabilityQuery($product, $productData);
        }

        if (!is_null($this->getSingleVendor())) {
            $product = $this->defaultVendorCondition($product, $this->getSingleVendor()->id);
        }

        $product = $product->whereTranslation('slug', $slug)->first();
        return $product;
    }

    public function checkRouteLocale($model, $slug)
    {
        if ($model->translate()->where('slug', $slug)->first()->locale != locale())
            return false;

        return true;
    }

    public function getProductsByCategory($request, $category)
    {
        $products = $this->product->orderBy('id', 'desc')->active()
            ->with(['offer' => function ($query) {
                $query->active()->unexpired()->started();
            }]);

        if (!is_null($this->getSingleVendor())) {
            $products = $this->defaultVendorCondition($products, $this->getSingleVendor()->id);
        }

        $products = $products->whereHas('categories', function ($query) use ($request, $category) {

            if (!empty($request->categories)) {
                $query->whereIn('product_categories.category_id', array_keys($request->categories));
            } elseif ($category != null) {
                $query->where('product_categories.category_id', $category->id);
            }
        });

        if (config('setting.products.toggle_ordering_time') == 1) {
            $products = $this->checkProductAvailabilityQuery($products);
        }

        if (isset($request->s) && !empty($request->s)) {
            $products = $products->where(function ($query) use ($request) {
                $query->whereHas('translations', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->s . '%');
                    $query->orWhere('slug', 'like', '%' . $request->s . '%');
                });
            });
        }

        if (!empty($request->tags)) {
            $products = $products->whereHas('tags', function ($query) use ($request) {
                $query->whereTranslation('slug', $request->tags);
            });
        }

        if ($request['price_from'] && $request['price_to']) {
            $products = $products->whereBetween('price', [$request['price_from'], $request['price_to']]);
        }

        $products = $products->paginate(10);

        return $products;
    }

    public function getRelatedProducts($product, $categories)
    {
        $products = $this->product->orderBy('id', 'desc')->active()
            ->with(['offer' => function ($query) {
                $query->active()->unexpired()->started();
            }])
            ->where('id', '<>', $product->id)
            ->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('product_categories.category_id', $categories);
            });

        if (!is_null($this->getSingleVendor())) {
            $products = $this->defaultVendorCondition($products, $this->getSingleVendor()->id);
        }

        if (config('setting.products.toggle_ordering_time') == 1) {
            $products = $this->checkProductAvailabilityQuery($products);
        }

        return $products->get();
    }

    public function findOneProduct($id)
    {
        $product = $this->product->active();

        if (!is_null($this->getSingleVendor())) {
            $product = $this->defaultVendorCondition($product, $this->getSingleVendor()->id);
        }

        $productData = ['key' => 'id', 'value' => $id];
        $product = $this->checkProductAvailabilityQuery($product, $productData);
        $product = $this->returnProductRelations($product, null);

        return $product->find($id);
    }

    public function findOneProductVariant($id)
    {
        $product = $this->variantPrd->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        if (!is_null($this->getSingleVendor())) {
            $product = $product->whereHas('product', function ($query) {
                $this->defaultVendorCondition($query, $this->getSingleVendor()->id);
                if (config('setting.products.toggle_ordering_time') == 1) {
                    $this->checkProductAvailabilityQuery($query);
                }
            });
        }

        return $product->find($id);
    }

    public function findById($id)
    {
        $product = $this->product->withDeleted()
            ->with([
                'tags', 'images',
                'addOns' => function ($q) {
                    $q->with('addOnOptions');
                },
                'options.option' => function ($q) {
                    $q->active()->with(['values' => function ($query) {
                        $query->active();
                    }]);
                }
            ]);

        if (!is_null($this->getSingleVendor())) {
            $product = $this->defaultVendorCondition($product, $this->getSingleVendor()->id);
        }

        if (config('setting.products.toggle_ordering_time') == 1) {
            $productData = ['key' => 'id', 'value' => $id];
            $product = $this->checkProductAvailabilityQuery($product, $productData);
        }

        return $product->find($id);
    }

    public function findVariantProductById($id)
    {
        $product = $this->variantPrd->with(['product', 'offer', 'productValues' => function ($q) {
            $q->with(['optionValue', 'productOption' => function ($q) {
                $q->with('option');
            }]);
        }]);

        if (!is_null($this->getSingleVendor())) {
            $product = $product->whereHas('product', function ($query) {
                $this->defaultVendorCondition($query, $this->getSingleVendor()->id);
                if (config('setting.products.toggle_ordering_time') == 1) {
                    $this->checkProductAvailabilityQuery($query);
                }
            });
        }

        return $product->find($id);
    }

    public function getVariantProductsByPrdId($id)
    {
        $products = $this->variantPrd->with(['offer', 'productValues' => function ($q) {
            $q->with(['optionValue', 'productOption' => function ($q) {
                $q->with('option');
            }]);
        }])->where('product_id', $id);

        if (!is_null($this->getSingleVendor())) {
            $products = $products->whereHas('product', function ($query) use ($id) {
                $this->defaultVendorCondition($query, $this->getSingleVendor()->id);
                if (config('setting.products.toggle_ordering_time') == 1) {
                    $productData = ['key' => 'id', 'value' => $id];
                    $this->checkProductAvailabilityQuery($query, $productData);
                }
            });
        }

        return $products->get();
    }

    public function returnProductRelations($model, $request)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            'productVendors',
            'subCategories',
            'addOns',
            'workingTimes',
            'variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }
}
