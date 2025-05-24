<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Modules\Tags\Repositories\FrontEnd\TagsRepository as Tags;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\Dashboard\StateVendorsResource;

class CategoryController extends Controller
{
    use CatalogTrait, VendorTrait;

    protected $vendor;
    protected $product;
    protected $category;
    protected $tags;

    function __construct(Vendor $vendor, Product $product, Category $category, Tags $tags)
    {
        $this->product = $product;
        $this->vendor = $vendor;
        $this->category = $category;
        $this->tags = $tags;
    }

    public function index($slug)
    {
        abort(404);
    }

    public function productsCategory(Request $request, $slug = null)
    {
        if ($slug == null) {
            $category = null;
        } else {
            $category = $this->category->findBySlug($slug);

            if (!$category)
                abort(404);
        }

        $vendorId = null;
        if (!is_null($this->getSingleVendor())) {
            $vendorId = $this->getSingleVendor()->id;
        }
        $request->request->add(['vendor_id' => $vendorId]);

        $filteredCategories = $this->category->getAllActiveWithProducts($request);
        $categories = $filteredCategories->filter(function ($value, $key) {
            return $value->products->count() > 0;
        });
        $categories = $categories->all();
        $cartItems = getCartContent();

        /*
        $products = $this->product->getProductsByCategory($request, $category);
        ### automatically append query string to pagination links
        $querystringArray = [];
        $querystringArray['s'] = $request->s;
        $querystringArray['categories'] = $request->categories;
        $querystringArray['tags'] = $request->tags;
        $querystringArray['price_from'] = $request->price_from;
        $querystringArray['price_to'] = $request->price_to;
        $products->appends($querystringArray);

        $tags = $this->tags->getAllActive();
        */

        return view('catalog::frontend.categories.category-products',
            compact('category', 'categories', 'cartItems', 'vendorId')
        );
    }


}
