<?php
namespace Modules\DriverApp\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\DriverApp\Http\Requests\WebService\UpdateStatuesRequest;
use Modules\DriverApp\Transformers\WebService\OpningStatusResource;
use Modules\DriverApp\Transformers\WebService\StatusResource;
use Modules\Vendor\Entities\VendorStatus;


class StatusController extends  WebServiceController {
    public function index()
    {


        $users = auth()->user()
            ->sellersVendors()
            ->get();
        return $this->response(StatusResource::collection($users));
    }


    public function list()
    {
        $list  = VendorStatus::get();


        $data =  OpningStatusResource::collection($list);
        $filteredData = $data->reject(function ($item) {
            return $item['id'] == '3';
        });

        return $this->response($filteredData->values());
    }


    public function update($id,UpdateStatuesRequest $request)
    {
        $record  =
            auth()->user()->sellersVendors()->findOrFail($id);

        $record->update(['vendor_status_id' => $request->vendor_status_id]);

        return $this->response("");
    }
}
