<?php

namespace App\Http\Controllers\API;

use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // Añadimos eager loading de appointments para evitar el N + 1
        $query = Client::with('appointments')->latest();

        // Aplicar filtrado opcional por razón social o municipio
        $query->when($request->company_name, function($query) use($request) {
            $query->where('company_name', 'like', '%' . $request->company_name . '%');
        })->when($request->municipality, function($query) use($request) {
            $query->where('municipality', 'like', '%' . $request->municipality . '%');
        });

        if ($request->has('contract_state')) {
            if ($request->contract_state === "active") {
                $query->where('clients.contract_start_date', '<=', now())
                    ->where('clients.contract_end_date', '>=', now());
            } else if ($request->contract_state === "not_active") {
                $query->where(function ($query) {
                    $query->where('clients.contract_start_date', '>=', now())
                        ->orWhere('clients.contract_end_date', '<=', now());
                });
            }
        }

        return ClientResource::collection($query->paginate(10));
    }

    public function store(ClientRequest $request): ClientResource|\Illuminate\Http\JsonResponse
    {
        try {
            $client = Client::create($request->validated());
            return new ClientResource($client);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Client $client): ClientResource
    {
        return ClientResource::make($client);
    }

    public function update(ClientRequest $request, Client $client): ClientResource|\Illuminate\Http\JsonResponse
    {
        try {
            $client->update($request->validated());
            return new ClientResource($client);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

//    public function destroy(Client $client): \Illuminate\Http\JsonResponse
//    {
//        try {
//            $client->delete();
//            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
//        } catch (\Exception $exception) {
//            report($exception);
//            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }
}
