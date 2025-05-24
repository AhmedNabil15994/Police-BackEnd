<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Advertising\Transformers\WebService\AdvertisingResource;
use Modules\Tags\Transformers\WebService\TagsResource;
use Modules\Vendor\Transformers\WebService\BranchesResource;
use Modules\Vendor\Transformers\WebService\OpeningStatusResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => number_format($this->price, 3),
            'qty' => $this->qty,
            'image' => url($this->image),
            'title' => optional($this->translate(locale()))->title,
//            'description' => htmlView($this->translate(locale())->description),
            'description' => optional($this->translate(locale()))->description ? cleanText(optional($this->translate(locale()))->description) : null,
            'short_description' => optional($this->translate(locale()))->short_description,
            'dimensions' => $this->shipment,
            'branches' => BranchesResource::collection($this->productVendors),
            'offer' => new ProductOfferResource($this->offer),
            'images' => ProductImagesResource::collection($this->images),
            'tags' => TagsResource::collection($this->tags),
            'addons' => AddOnsResource::collection($this->addOns),
            'products_options' => ProductOptionResource::collection($this->options),
            'variations_values' => ProductVariantResource::collection($this->variants),
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
            'sharable_link' => route('frontend.products.index', optional($this->translate(locale()))->slug),
//            'adverts' => AdvertisingResource::collection($this->adverts),
        ];

        if (auth('api')->check()) {
            $result['is_favorite'] = CheckProductInUserFavourites($this->id, auth('api')->id());
        } else {
            $result['is_favorite'] = null;
        }

        return $result;
    }
}
