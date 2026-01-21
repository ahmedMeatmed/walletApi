<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    //
     public function index()
    {
        return Service::where('is_active', true)->get();
    }

     public function store(StoreServiceRequest $request)
    {
      
        $service = Service::create($request->all());

        return response()->json($service, 201);
    }

      public function show(Service $service)
    {
        return $service;
    }

       public function update(UpdateServiceRequest $request, Service $service)
    {

        $service->update($request->all());

        return response()->json($service);
    }

       public function destroy(Service $service)
    {
        $service->softDeletes();
        return response()->json(['message' => 'Service deleted']);
    }

    public function restore($id)
    {
        $service = Service::withTrashed()->find($id);
        if ($service && $service->trashed()) {
            $service->restore();
            return response()->json(['message' => 'Service restored']);
        }
        return response()->json(['message' => 'Service not found or not deleted'], 404);
    }
}
