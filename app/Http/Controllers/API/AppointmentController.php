<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()->with('client');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('company_name')) {
            $query->whereHas('client', function($q) use($request) {
                $q->where('company_name', 'like', '%' . $request->company_name . '%');
            });
        }

        return AppointmentResource::collection($query->orderByDesc('date')->paginate(10));
    }

    public function store(AppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());
        return new AppointmentResource($appointment);
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        if ($appointment->performed_examinations !== 0) {
            return response()->json([
                'error' => 'No se puede actualizar la cita porque ya se han realizado reconocimientos médicos.'
            ], Response::HTTP_FORBIDDEN);
        }

        $appointment->update($request->validated());
        return new AppointmentResource($appointment);
    }

    public function fill(Request $request, Appointment $appointment)
    {
        if ($appointment->performed_examinations !== 0) {
            return response()->json([
                'error' => 'No se puede actualizar la cita porque ya se han realizado reconocimientos médicos.'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'performed_examinations' => 'required|integer|min:0',
        ]);

        $appointment->update($validated);
        return new AppointmentResource($appointment);
    }

//    public function destroy(Appointment $appointment)
//    {
//        $appointment->delete();
//        return response()->json(['message' => 'Cita eliminada'], 200);
//    }
}
