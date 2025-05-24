<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Traits\CoreTrait;

class OrderProductResource extends JsonResource
{
    use CoreTrait ;
    public function toArray($request)
    {
        $options = [];
        $result = [
//            'id' => $this->id,
            'selling_price' => $this->price,
            'qty' => $this->qty,
            'total' => $this->total,
            'notes' => $this->notes
        ];

        if (isset($this->product_variant_id) && !empty($this->product_variant_id)) {
            $result['title'] = optional(optional(optional($this->variant)->product))->title;
            $result['image'] = url(optional($this->variant)->image);
            $result['sku'] = optional($this->variant)->sku;
//            $result['product_details'] = new ProductResource($this->variant->product);
        } else {
            $result['title'] = optional(optional($this->product))->title;
            $result['image'] = url(optional($this->product)->image);
            $result['sku'] = optional($this->product)->sku;
//            $result['product_details'] = new ProductResource($this->product);
        }


        if (!is_null($this->add_ons_option_ids) && !empty($this->add_ons_option_ids)){
            foreach (json_decode($this->add_ons_option_ids)->data as $key => $addons){
                foreach ($addons->options as $k => $option){
                    $options[] = [
                        'id' => $option,
                        'title' => getAddonsOptionTitle($option),
                        'sku' => getAddonsOptionSku($option),
                        'price' => getOrderAddonsOptionPrice(json_decode($this->add_ons_option_ids), $option),
                        'qty' => 1,
                    ];
                }
            }
        }

        $result['addons'] = $options;
        return $result;
    }
}
