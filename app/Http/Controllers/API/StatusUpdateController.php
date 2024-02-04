<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StatusUpdate;
use Exception;

class StatusUpdateController extends Controller
{
    //return all update form
    public function index(Request $request)
    {
        $StatusUpdate = StatusUpdate::all();
        return response()->json($StatusUpdate, 200);
    }

    //create new update from
    public function store(Request $request)
    {
        $request->validate([
            'EmergMedCareGiven' => 'nullable|string',
            'PatientResponseToEmergMedCare' => 'nullable|string',
            'Time' => 'nullable|date_format:H:i',
            'B_P' => [
                'nullable',
                'string',
                'regex:/^\d{2,3}\/\d{2,3}$/',
                function ($attribute, $value, $fail) {
                    list($firstValue, $secondValue) = explode('/', $value);
                    if (($firstValue < 45 || $firstValue > 160) && ($secondValue < 30 || $secondValue > 100)) {
                        $fail("The first AND seconed valuse incorrect");
                    }
                    else if($firstValue < 45 || $firstValue > 160){
                        $fail("The first value of $attribute must be between 45 and 160.");
                    }
                    else if ($secondValue < 30 || $secondValue > 100) {
                        $fail("The second value after '/' of $attribute must be between 30 and 100.");
                    }
                },
            ],
            'PULSE' => 'nullable|numeric',
            'RESP' => 'nullable|numeric',
            'TEMP' => 'nullable|numeric',
            'PulseO2' => 'nullable|numeric',
            'ECG' => 'nullable|numeric',
            'Death' => 'nullable|string|in:DOA,Refused CPR,During intervention,During Transport',
            'TimeOfDeath' => 'nullable|date_format:H:i',
            'AuthorityNotified' => 'nullable|string|in:yes,no'
        ]);
        $user = auth()->user();
        $lastPatient = $user->patients->sortByDesc('created_at')->first();
        if ($lastPatient) {
        // Set the 'Time' field to the current time
        $request->merge(['Time' => now()->format('H:i')]);
        $request->merge(['patient_id' => $lastPatient->id]);
        } else {
            return response()->json(['error' => 'No Patient Form Found.'], 404);
        }
        try {
            $statusUpdate = StatusUpdate::create($request->all());
        } catch (Exception $e) {
            return response()->json(['message' => 'Update Form Not Sent'], 404);
        }
        return response()->json(['message' => 'Update Form Sent Successfully', 'Update Form' => $statusUpdate], 201);
    }

    //return a specific user info
    public function show($id)
    {
        $StatusUpdate = StatusUpdate::find($id);
        if(is_null($StatusUpdate)){
            return response()->json ([
                'message' => 'No StatusUpdate Form Found'
            ] , 404);
        }
        return StatusUpdate::findOrFail($id);
    }

    //update StatusUpdate form
    public function update(Request $request, $id)
    {
        $StatusUpdate = StatusUpdate::find($id);
        if(is_null($StatusUpdate)){
            return response()->json ([
                'message' => 'No StatusUpdate Form Found'
            ] , 404);
        }
        return response()->json(['message' => 'You Can Not Make Any Changes Here'] , 200);
    }

    //deltet update form
    public function destroy($id)
    {
        $StatusUpdate = StatusUpdate::find($id);
        if(is_null($StatusUpdate)){
            return response()->json ([
                'message' => 'No StatusUpdate Form Found'
            ] , 404);
        }
        $StatusUpdate->delete();
        return response()->json(['message' => 'StatusUpdate Form Deleted'] , 204);
    }
}
