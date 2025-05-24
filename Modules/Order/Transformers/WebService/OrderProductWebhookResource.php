<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\ProductResource;

class OrderProductWebhookResource extends JsonResource
{
    public function toArray($request)
    {
        $options = [];
        $result = [
            'selling_price' => $this->price,
            'qty' => $this->qty,
            'total' => $this->total,
            'notes' => $this->notes,
        ];

        if (isset($this->product_variant_id) && !empty($this->product_variant_id)) {
            $prdTitle = '';
            foreach ($this->orderVariantValues as $k => $orderVal) {
                $prdTitle .= optional(optional(optional($orderVal->productVariantValue)->optionValue)->translate(locale()))->title . ' ,';
            }
            $result['title'] = $this->variant->product->translate(locale())->title . ' - ' . rtrim($prdTitle, ' ,');
            $result['image'] = url($this->variant->image);
            $result['sku'] = $this->variant->sku;
        } else {
            $result['title'] = $this->product->translate(locale())->title;
            $result['image'] = url($this->product->image);
            $result['sku'] = $this->product->sku;
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

        $result['options'] = $options;
        return $result;
    }
}
