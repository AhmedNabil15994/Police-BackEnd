<?php

use Illuminate\Support\Str;
use Modules\Cart\Entities\DatabaseStorageModel;
use Modules\Order\Transformers\WebService\OrderWebhookResource;

if (!function_exists('encryptOrderId')) {
    function encryptOrderId($id)
    {
        $key = 9999999999;
        return base_convert($id * $key, 10, 36);
    }
}

if (!function_exists('decryptOrderId')) {
    function decryptOrderId($id)
    {
        $key = 9999999999;
        return (int) base_convert($id, 36, 10) / $key;
    }
}

if (!function_exists('hash_equals_custom')) {
    function hash_equals_custom($knownString, $userString) {
        if (function_exists('mb_strlen')) {
            $kLen = mb_strlen($knownString, '8bit');
            $uLen = mb_strlen($userString, '8bit');
        } else {
            $kLen = strlen($knownString);
            $uLen = strlen($userString);
        }
        if ($kLen !== $uLen) {
            return false;
        }
        $result = 0;
        for ($i = 0; $i < $kLen; $i++) {
            $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
        }
        return 0 === $result;
    }
}

// Active Dashboard Menu
if (!function_exists('active_menu')) {
    function active_menu($routeNames)
    {
        $routeNames = (array)$routeNames;
        return in_array(request()->segment(3), $routeNames) ? 'active' : '';

        /*foreach ($routeNames as $routeName) {
            return (strpos(Route::currentRouteName(), $routeName) == 0) ? '' : 'active';
        }*/
    }
}

if (!function_exists('pushDataToWebhook')) {
    function pushDataToWebhook($order)
    {

        $url = config('setting.other.webhook_url');
        if($url){
            $data = new OrderWebhookResource($order);
            $token = config("setting.other.webhook_token");

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: {$token}",
            ));

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data,true),
            ));
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $response = curl_exec($curl);
            curl_close($curl);
        }

        return true;
    }
}


if (!function_exists('slugAr')) {
    /**
     * The Current dir
     *
     * @param string $locale
     */
    function slugAr($string, $separator = '-')
    {
        if (is_null($string)) {
            return "";
        }

        // Remove spaces from the beginning and from the end of the string
        $string = trim($string);

        // Lower case everything
        // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
        $string = mb_strtolower($string, "UTF-8");;

        // Make alphanumeric (removes all other characters)
        // this makes the string safe especially when used as a part of a URL
        // this keeps latin characters and arabic charactrs as well
        $string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

        // Remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        // Convert whitespaces and underscore to the given separator
        $string = preg_replace("/[\s_]/", $separator, $string);

        return $string;
    }
}

// GET THE CURRENT LOCALE
if (!function_exists('locale')) {

    function locale()
    {
        return app()->getLocale();
    }
}

// active header categories menu
if (!function_exists('activeCategoryTab')) {

    function activeCategoryTab($category, $index, $returnValue)
    {
        if (request()->has('category') && !empty(request()->get('category'))) {
            if (request()->get('category') == $category->slug) {
                return $returnValue;
            }
        } else {
            if ($index == 0) {
                return $returnValue;
            }
        }
        return is_bool($returnValue) === true ? false : '';
    }
}

// SAVE COOKIE with key and value
if (!function_exists('set_cookie_value')) {

    function set_cookie_value($key, $value, $expire = null)
    {
        $expire = $expire ?? time() + (2 * 365 * 24 * 60 * 60); // set a cookie that expires in 2 years
        setcookie($key, $value, $expire, '/');
        return true;
    }
}

// GET THE COOKIE value for Specific key
if (!function_exists('get_cookie_value')) {

    function get_cookie_value($key)
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
}

// CHECK IF CURRENT LOCALE IS RTL
if (!function_exists('is_rtl')) {

    function is_rtl($locale = null)
    {

        $locale = ($locale == null) ? locale() : $locale;

        if (in_array($locale, config('rtl_locales'))) {
            return 'rtl';
        }

        return 'ltr';
    }
}


if (!function_exists('slugfy')) {
    /**
     * The Current dir
     *
     * @param string $locale
     */
    function slugfy($string, $separator = '-')
    {
        $url = trim($string);
        $url = strtolower($url);
        $url = preg_replace('|[^a-z-A-Z\p{Arabic}0-9 _]|iu', '', $url);
        $url = preg_replace('/\s+/', ' ', $url);
        $url = str_replace(' ', $separator, $url);

        return $url;
    }
}


if (!function_exists('path_without_domain')) {
    /**
     * Get Path Of File Without Domain URL
     *
     * @param string $locale
     */
    function path_without_domain($path)
    {
        $url = $path;
        $parts = explode("/", $url);
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
        $newurl = implode("/", $parts);

        return $newurl;
    }
}


if (!function_exists('int_to_array')) {
    /**
     * convert a comma separated string of numbers to an array
     *
     * @param string $integers
     */
    function int_to_array($integers)
    {
        return array_map("intval", explode(",", $integers));
    }
}


if (!function_exists('combinations')) {

    function combinations($arrays, $i = 0)
    {

        if (!isset($arrays[$i])) {
            return array();
        }

        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }
}


if (!function_exists('htmlView')) {
    /**
     * Access the OrderStatus helper.
     */
    function htmlView($content)
    {
        return
            '<!DOCTYPE html>
           <html lang="en">
             <head>
               <meta charset="utf-8">
               <meta http-equiv="X-UA-Compatible" content="IE=edge">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               <link href="css/bootstrap.min.css" rel="stylesheet">
               <!--[if lt IE 9]>
                 <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
               <![endif]-->
             </head>
             <body>
               ' . $content . '
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
               <script src="js/bootstrap.min.js"></script>
             </body>
           </html>';
    }
}

if (!function_exists('getDays')) {
    function getDays($dayCode = null)
    {

        if ($dayCode == null) {
            return [
                'sat' => __('company::dashboard.companies.availabilities.days.sat'),
                'sun' => __('company::dashboard.companies.availabilities.days.sun'),
                'mon' => __('company::dashboard.companies.availabilities.days.mon'),
                'tue' => __('company::dashboard.companies.availabilities.days.tue'),
                'wed' => __('company::dashboard.companies.availabilities.days.wed'),
                'thu' => __('company::dashboard.companies.availabilities.days.thu'),
                'fri' => __('company::dashboard.companies.availabilities.days.fri'),
            ];
        } else {
            switch ($dayCode) {
                case 'sat':
                    return __('company::dashboard.companies.availabilities.days.sat');
                    break;
                case 'sun':
                    return __('company::dashboard.companies.availabilities.days.sun');
                    break;
                case 'mon':
                    return __('company::dashboard.companies.availabilities.days.mon');
                case 'tue':
                    return __('company::dashboard.companies.availabilities.days.tue');
                    break;
                case 'wed':
                    return __('company::dashboard.companies.availabilities.days.wed');
                    break;
                case 'thu':
                    return __('company::dashboard.companies.availabilities.days.thu');
                    break;
                case 'fri':
                    return __('company::dashboard.companies.availabilities.days.fri');
                    break;
                default:
                    return 'not_exist';
            }
        }
    }
}

if (!function_exists('getFullDayByCode')) {
    function getFullDayByCode($dayCode)
    {
        switch ($dayCode) {
            case 'sat':
                return 'saturday';
                break;
            case 'sun':
                return 'sunday';
                break;
            case 'mon':
                return 'monday';
            case 'tue':
                return 'tuesday';
                break;
            case 'wed':
                return 'wednesday';
                break;
            case 'thu':
                return 'thursday';
                break;
            case 'fri':
                return 'friday';
                break;
            default:
                return null;
        }
    }
}

if (!function_exists('checkSelectedCartGiftProducts')) {
    function checkSelectedCartGiftProducts($prdId, $giftId)
    {
        $giftCondition = Cart::getCondition('gift');

        if ($giftCondition) {
            $giftsArray = $giftCondition->getAttributes()['gifts'];

            foreach ($giftsArray as $item) {
                if (in_array($prdId, $item['products']) && $item['id'] == $giftId)
                    return true;
            }
        }

        return false;
    }
}

if (!function_exists('checkSelectedCartCards')) {
    function checkSelectedCartCards($cardId)
    {
        $condition = Cart::getCondition('card');

        if ($condition && isset($condition->getAttributes()['cards'][$cardId])) {
            return $condition->getAttributes()['cards'][$cardId];
        }

        return null;
    }
}

if (!function_exists('checkSelectedCartAddons')) {
    function checkSelectedCartAddons($addonsId)
    {
        $condition = Cart::getCondition('addons');

        if ($condition && isset($condition->getAttributes()['addons'][$addonsId])) {
            return $condition->getAttributes()['addons'][$addonsId];
        }

        return null;
    }
}

if (!function_exists('checkSelectedVendorDeliveryCompany')) {
    function checkSelectedVendorDeliveryCompany($vendorId, $companyId)
    {
        $condition = Cart::getCondition('company_delivery_fees');

        if ($condition && isset($condition->getAttributes()['vendors'][$vendorId][$companyId])) {
            return 'checked';
        }

        return null;
    }
}

if (!function_exists('getDayByDayCode')) {
    function getDayByDayCode($dayCode)
    {
        if (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($dayCode)))) {
            return [
                'full_date' => date('Y-m-d', strtotime($dayCode)),
                'day' => date('d', strtotime($dayCode)),
            ];
        }

        return '';
    }
}

if (!function_exists('generateVariantProductData')) {
    function generateVariantProductData($product, $variantPrdId, $optionValues)
    {
        if (!empty($optionValues) && count($optionValues) > 0) {
            $generatedName = $product->translate(locale())->title . ' - ';
            $generatedSlug = 'var=' . $variantPrdId . '&';
            foreach ($optionValues as $k => $value) {
                $optionValue = \Modules\Variation\Entities\OptionValue::with('option')->find($value);
                $generatedName .= $k == 0 ? $optionValue->translate(locale())->title : ', ' . $optionValue->translate(locale())->title;

                $valueSlug = Str::slug($optionValue->translate('en')->title);
                $generatedSlug .= 'attr_' . Str::slug(Str::lower($optionValue->option->translate('en')->title)) . '=';
                $generatedSlug .= $k === array_key_last($optionValues) ? $valueSlug : $valueSlug . '&';
            }
            return [
                'name' => $generatedName,
                'slug' => $generatedSlug,
            ];
        } else {
            return [
                'name' => '',
                'slug' => '',
            ];
        }
    }
}

if (!function_exists('getOptionQueryString')) {
    function getOptionQueryString($string)
    {
        $pieces = explode('_', $string);
        return $pieces[1];
    }
}

if (!function_exists('getOptionsAndValuesIds')) {
    function getOptionsAndValuesIds($request)
    {
        $selectedOptions = [];
        $selectedOptionsValue = [];

        foreach ($request->query() as $k => $query) {
            if (Str::startsWith($k, 'attr_')) {
                $optionTitle = Str::title(str_replace('-', ' ', getOptionQueryString($k)));
                $option = \Modules\Variation\Entities\Option::active()->whereTranslation('title', $optionTitle)->first();
                $selectedOptions[] = $option ? $option->id : "";

                $optionValTitle = Str::title(str_replace('-', ' ', $query));
                $optionVal = \Modules\Variation\Entities\OptionValue::active()->where('option_id', $option ? $option->id : 0)->whereTranslation('title', $optionValTitle)->first();
                $selectedOptionsValue[] = $optionVal ? $optionVal->id : "";
            }
        }

        return [
            'selectedOptions' => $selectedOptions,
            'selectedOptionsValue' => $selectedOptionsValue,
        ];
    }
}

if (!function_exists('generateRandomCode')) {
    function generateRandomCode($length = 8)
    {
        return Str::upper(Str::random($length));
    }
}

if (!function_exists('limitString')) {
    function limitString($string, $length = 50, $end = '...')
    {
        return Str::limit($string, $length, $end);
    }
}

if (!function_exists('toggleSideMenuItemsByVendorType')) {
    function toggleSideMenuItemsByVendorType()
    {
        return config('setting.other.is_multi_vendors') == 1 ? 'block' : 'none';
    }
}

################## Start - Cart Functions ###################

if (!function_exists('getCartContent')) {
    function getCartContent($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->getContent();
        else
            $result = Cart::getContent();

        return $result;
    }
}

if (!function_exists('getCartItemById')) {
    function getCartItemById($id, $userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->get($id);
        else
            $result = Cart::get($id);

        return $result ?? null;
    }
}

if (!function_exists('getCartTotal')) {
    function getCartTotal($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->getTotal();
        else
            $result = Cart::getTotal();

        return $result ?? null;
    }
}

if (!function_exists('getCartSubTotal')) {
    function getCartSubTotal($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->getSubTotal();
        else
            $result = Cart::getSubTotal();

        return $result ?? null;
    }
}

if (!function_exists('getOrderShipping')) {
    function getOrderShipping($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = is_null(Cart::session($userToken)->getCondition('company_delivery_fees')) ? 0 : Cart::session($userToken)->getCondition('company_delivery_fees')->getValue();
        else
            $result = is_null(Cart::getCondition('company_delivery_fees')) ? 0 : Cart::getCondition('company_delivery_fees')->getValue();

        return $result ?? null;
    }
}

if (!function_exists('addItemCondition')) {
    function addItemCondition($productId, $itemCondition, $userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->addItemCondition($productId, $itemCondition);
        else
            $result = Cart::addItemCondition($productId, $itemCondition);

        return $result ?? null;
    }
}

if (!function_exists('getCartConditionByName')) {
    function getCartConditionByName($userToken = null, $name = '')
    {
        if (is_null($userToken)) {
            if (auth()->check())
                $userToken = auth()->user()->id ?? null;
            else
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (!is_null($userToken))
            $result = Cart::session($userToken)->getCondition($name) ?? null;
        else
            $result = Cart::getCondition($name) ?? null;

        return $result ?? null;
    }
}

if (!function_exists('getCartItemsCouponValue')) {
    function getCartItemsCouponValue($userToken = null)
    {
        $value = null;
        $items = getCartContent($userToken);
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                foreach ($item->getConditions() as $condition) {
                    if ($condition->getName() == 'product_coupon') {
                        $value += intval($item->quantity) * abs($condition->getValue());
                    }
                }
            }
        }
        return $value;
    }
}

if (!function_exists('getProductCartCount')) {
    function getProductCartCount($id)
    {
        $result = DatabaseStorageModel::where('id', 'LIKE', '%cart_items%')->get()->reject(function ($item) {
            return count($item->cart_data) == 0;
        })->map(function ($item) use ($id) {
            return $item->cart_data->each(function ($collection, $key) use ($id) {
                if ($key == $id)
                    return $collection;

//                dump($collection->toArray(), 'key::' . $key, ':::id:::' . $id);
            });
        });

        return array_values($result->toArray());
    }
}

################## End - Cart Functions ###################

if (!function_exists('getAllActiveBranchesByRestaurantId')) {
    function getAllActiveBranchesByRestaurantId($id, $displayType = 'select')
    {
        $branches = \Modules\Vendor\Entities\Vendor::active()
            ->where('parent_id', $id)
            ->orderBy('id', 'desc');

        if ($displayType == 'select')
            $branches = $branches->get(['id', 'is_main_branch']);
        else
            $branches = $branches->get();

        return $branches;
    }
}

if (!function_exists('getRestaurantMainBranch')) {
    function getRestaurantMainBranch($id)
    {
        return \Modules\Vendor\Entities\Vendor::active()
            ->where('parent_id', $id)
            ->where('is_main_branch', 1)
            ->first();
    }
}

if (!function_exists('getBranchById')) {
    function getBranchById($id)
    {
        return \Modules\Vendor\Entities\Vendor::find($id);
    }
}

if (!function_exists('checkRestaurantEnableDelivery')) {
    function checkRestaurantEnableDelivery($id)
    {
        return \Modules\Vendor\Entities\Vendor::active()
            ->where('parent_id', $id)
            ->where('is_main_branch', 1)
            ->first();
    }
}

if (!function_exists('getCurrentLanguageNativeName')) {
    function getCurrentLanguageNativeName()
    {
        return isset(config('laravellocalization.supportedLocales')[locale()]) ? config('laravellocalization.supportedLocales')[locale()]['native'] : '---';
    }
}

if (!function_exists('activeFrontendHeaderMenu')) {
    function activeFrontendHeaderMenu($routeNames)
    {
        $routeNames = (array)$routeNames;
        return in_array(request()->route()->getName(), $routeNames) ? 'active' : '';
    }
}

if (!function_exists('selectedCartAddonsOption')) {
    function selectedCartAddonsOption($product, $addonsId, $optionId)
    {
        if (getCartItemById($product->id)->attributes->has('addonsOptions')) {
            $collection = collect(getCartItemById($product->id)->attributes->addonsOptions['data'])->where('id', $addonsId)->pluck('options')->toArray();
            $collection = array_first($collection);
            if (!is_null($collection) && in_array($optionId, $collection))
                return 'checked';
        }
        return '';
    }
}

if (!function_exists('getAddonsTitle')) {
    function getAddonsTitle($id)
    {
        $item = \Modules\Catalog\Entities\AddonCategory::find($id);
        return $item ? $item->getTranslation('title', locale()) : '---';
    }
}

if (!function_exists('getAddonsOptionTitle')) {
    function getAddonsOptionTitle($id)
    {
        $item = \Modules\Catalog\Entities\AddonOption::find($id);
        return $item ? $item->getTranslation('title', locale()) : '---';
    }
}

if (!function_exists('getAddonsOptionSku')) {
    function getAddonsOptionSku($id)
    {
        $item = \Modules\Catalog\Entities\AddonOption::find($id);
        return $item ? $item->sku : '---';
    }
}

if (!function_exists('checkIfAddonOptionIsDefault')) {
    function checkIfAddonOptionIsDefault($productId, $addonCategoryId, $addon_option_id)
    {
        $productAddon = \Modules\Catalog\Entities\ProductAddon::where('product_id', $productId)->where('addon_category_id', $addonCategoryId)->first();
        $item = $productAddon && $productAddon->addonOptions ? $productAddon->addonOptions->where('addon_option_id', $addon_option_id)->first() : null;
        return $item && $item->default == 1;
    }
}

if (!function_exists('getOrderAddonsOptionPrice')) {
    function getOrderAddonsOptionPrice($addOnsOptionIds, $optionId)
    {
        if (isset($addOnsOptionIds->addonsPriceObject)) {
            $index = array_search($optionId, array_column($addOnsOptionIds->addonsPriceObject, 'id'));
            return optional($addOnsOptionIds->addonsPriceObject[$index])->amount;
        }
        return '';
    }
}

if (!function_exists('cleanText')) {
    function cleanText($text = '')
    {
        return trim(preg_replace("/\r\n|\r|\n/", '', strip_tags(str_replace("&nbsp;", ' ', $text))));
    }
}

if (!function_exists('checkPickupDeliveryCookie')) {
    function checkPickupDeliveryCookie()
    {
        if (is_null(json_decode(get_cookie_value(config('core.config.constants.PICKUP_DELIVERY'))))) {
            return null;
        } else {
            return json_decode(get_cookie_value(config('core.config.constants.PICKUP_DELIVERY')));
        }
    }
}

if (!function_exists('getContactInfoCookie')) {
    function getContactInfoCookie()
    {
        if (is_null(json_decode(get_cookie_value(config('core.config.constants.CONTACT_INFO'))))) {
            return null;
        } else {
            return json_decode(get_cookie_value(config('core.config.constants.CONTACT_INFO')));
        }
    }
}

if (!function_exists('getBranchesByState')) {
    function getBranchesByState($stateId)
    {
        $branches = \Modules\Vendor\Entities\Vendor::whereHas('deliveryCharge', function ($q) use ($stateId) {
            $q->active();
            $q->where('state_id', $stateId);
        })->with(['deliveryCharge' => function ($q) use ($stateId) {
            $q->active();
            $q->where('state_id', $stateId);
        }])->get();
        return $branches;
    }
}

if (!function_exists('CheckProductInUserFavourites')) {
    function CheckProductInUserFavourites($productId, $userId)
    {
        $favourite = \Modules\User\Entities\UserFavourite::where('product_id', $productId)
            ->where('user_id', $userId)->first();
        return !is_null($favourite);
    }
}


