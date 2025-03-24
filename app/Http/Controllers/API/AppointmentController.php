<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\ClientResource;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()->with('client');

        // Filtrar por fecha (rango)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        // Filtrar por cliente
        if ($request->filled('company_name')) {
            $query->whereHas('client', function($q) use($request) {
                $q->where('company_name', 'like', '%' . $request->company_name . '%');
            });
        }

        // Filtrar por estado de la cita
//        if ($request->filled('state')) {
//            $query->where('state', $request->state);
//        }

        return AppointmentResource::collection($query->latest()->paginate(10));
    }

    public function store(AppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());
        return new AppointmentResource($appointment);
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        return new AppointmentResource($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'Cita eliminada'], 200);
    }
}
