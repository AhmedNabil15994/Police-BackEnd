<?php

namespace Modules\Apps\Http\Controllers\FrontEnd;

use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Vendor\Transformers\Dashboard\StateVendorsResource;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Apps\Http\Requests\FrontEnd\ContactUsRequest;
use Modules\Apps\Notifications\FrontEnd\ContactUsNotification;
use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Modules\Slider\Repositories\FrontEnd\SliderRepository as Slider;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Supplier\Repositories\FrontEnd\SupplierRepository as Supplier;
use Modules\Area\Repositories\FrontEnd\CityRepository as City;
use Cart;
use Spatie\ResponseCache\Facades\ResponseCache;

class HomeController extends Controller
{
    use CatalogTrait;

    protected $category;
    protected $slider;
    protected $vendor;
    protected $supplier;
    protected $city;

    function __construct(Category $category, Slider $slider, Vendor $vendor, Supplier $supplier, City $city)
    {
        $this->category = $category;
        $this->slider = $slider;
        $this->vendor = $vendor;
        $this->supplier = $supplier;
        $this->city = $city;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        ### Get Featured Products
        $featuredProducts = $this->category->getFeaturedProducts($request);

        ### Get Latest Offers
        $latestOffers = $this->category->getLatestOffersData($request);

        ### Get Main Categories Data
        $filteredCategories = $this->category->getMainCategoriesData($request);
        $categories = $filteredCategories->filter(function ($value, $key) {
            return $value->products->count() > 0;
        });
        $categories = $categories->all();

        $sliders = $this->slider->getAllActive();
        $suppliers = $this->supplier->getAllActive();

        return view('apps::frontend.index', compact(
            'featuredProducts',
            'latestOffers',
            'categories',
            'sliders',
//            'branches',
            'suppliers',
//            'cities',
        ));
    }

    public function contactUs()
    {
        return view('apps::frontend.contact-us');
    }

    public function sendContactUs(ContactUsRequest $request)
    {
        Notification::route('mail', config('setting.contact_us.email'))
            ->notify((new ContactUsNotification($request))->locale(locale()));

        return redirect()->back()->with(['status' => __('apps::frontend.contact_us.alerts.send_message')]);
    }

    public function getBranchesByState(Request $request)
    {
        $branches = $this->vendor->getBranchesByState($request->state_id);
        $result = null;
        if (count($branches) == 1) {
            $userToken = $this->getUserCartToken();
            $request->request->add(['vendor_id' => $branches[0]->id]);
            $result = $this->saveAndRemoveDeliveryCharge($request, $userToken);
        }
        $response = [
            "message" => 'Branches returned successfully',
            "data" => StateVendorsResource::collection($branches),
            "delivery" => $result
        ];
        ResponseCache::clear();
        return response()->json($response, 200);
    }
}
