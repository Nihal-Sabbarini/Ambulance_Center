<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Ramsey\Uuid\Type\Integer;
use App\Events\FormCreated;


class PatientController extends Controller
{
    //return all forms
    public function index(Request $request)
{
    // Assuming you are using Laravel's built-in authentication
    $user = $request->user();

    // Retrieve patients with the associated user name using a join
    $patients = Patient::join('users', 'patients.user_id', '=', 'users.id')
        ->select('patients.*', 'users.name as user_name')
        ->get();

    return response()->json(['patients' => $patients], 200);
}

    //create new patient form
    public function store(Request $request)
    {
        //to do implode for connect with frontend
        $availableMedicalHistory = ['Diabetes','COPD','cardiac','Seizure','Hypertension','cancer','ot'];
        $availableAllergies = ['None','PCN','Sulfa','Codeine','Iodine','Unknown'];
        $availableReasons = ['Protocol','Nearest Facility','Family case choice','Online instructions','Other'];

        $request->validate([
            'FullName' => 'nullable|string',
            'PatientID' => 'nullable|unique:patients,PatientID|string',
            'Address' => 'nullable|string',
            'AgeYearsOrMonths' => 'nullable|string|in:Years,Months',
            'Age' => 'nullable|integer',
            'PhoneNumber' => 'nullable|string',
            'Gender' => 'nullable|string|in:Male,Female',
            'ChiefComplaint' => 'nullable|string',
            'PertinentFindingsOnPhysicalExarn' => 'nullable|string',
            'MedicalHistory' => 'nullable|array',
            'MedicalHistory.*' =>  'in:' . implode(',', $availableMedicalHistory),
            'Allergies' =>'nullable|array',
            'Allergies.*' => 'in:' . implode(',', $availableAllergies),
            'Medication' => 'nullable|string|in:Denies,Name(s)',
            'MedicationName' => 'nullable|string',
            'EmergMedCareGiven' => 'nullable|string',
            'PatientResponseToEmergMedCare' => 'nullable|string',
            'Destination' => 'nullable|string|in:Heath center,Interfacility,Rendezvous,Residence,Other',
            'Reasons' =>'nullable|array' ,
            'Reasons.*' => 'in:' . implode(',', $availableReasons),
            'Date' => 'nullable|date|date_format:Y-m-d',
            'SiteOfInjury' => 'nullable|string',
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

        $request->merge([
            'Date' => now()->toDateString(),
            'Time' => now()->format('H:i'),
            'user_id' => auth()->user()->id
        ]);

        $data = [
            'Reasons' => implode(',', $request->input('Reasons', [])),
            'Allergies' => implode(',', $request->input('Allergies', [])),
            'MedicalHistory' => implode(',', $request->input('MedicalHistory', [])),
        ];

        $data = array_merge($data, $request->except(['Reasons', 'Allergies', 'MedicalHistory']));
        $Patient = Patient::create($data);
        return response()->json($Patient, 201);

    }

    //return a specific form
    public function show($id)
    {
        $patient = Patient::find($id);
        if(is_null($patient)){
            return response()->json ([
                'message' => 'No Patient Form Found'
            ] , 404);
        }
        return Patient::findOrFail($id);
    }

    //Update patient form
    public function update(Request $request, $id)
    {
        $patient = patient::find($id);
        if(is_null($patient)){
            return response()->json ([
                'message' => 'No patient Found'
            ] , 404);
        }
        $availableMedicalHistory = ['Diabetes','COPD','cardiac','Seizure','Hypertension','cancer','ot'];
        $availableAllergies = ['None','PCN','Sulfa','Codeine','Iodine','Unknown'];
        $availableReasons = ['Protocol','Nearest Facility','Family case choice','Online instructions','Other'];

        $request->validate([
            'user_id' => 'integer|exists:users,id',
            'FullName' => 'nullable|string',
            'PatientID' => 'nullable|unique|string',
            'Address' => 'nullable|string',
            'AgeYearsOrMonths' => 'nullable|string|in:Years,Months',
            'Age' => 'nullable|integer',
            'PhoneNumber' => 'nullable|string',
            'Gender' => 'nullable|string|in:Male,Female',
            'ChiefComplaint' => 'nullable|string',
            'PertinentFindingsOnPhysicalExarn' => 'nullable|string',
            'MedicalHistory' => ['nullable','string', 'in:' . implode(',', $availableMedicalHistory)],
            'Allergies' => ['nullable','string', 'in:' . implode(',', $availableAllergies)],
            'Medication' => 'nullable|string|in:Denies,Name(s)',
            'MedicationName' => 'nullable|string',
            'EmergMedCareGiven' => 'nullable|string',
            'PatientResponseToEmergMedCare' => 'nullable|string',
            'Destination' => 'nullable|string|in:Heath center,Interfacility,Rendezvous,Residence,Other',
            'Reasons' => ['nullable','string', 'in:' . implode(',', $availableReasons)],
            'SiteOfInjury' => 'nullable|string',
            'Time' => 'nullable|float',
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
            'PULSE' => 'nullable|float',
            'RESP' => 'nullable|float',
            'TEMP' => 'nullable|float',
            'PulseO2' => 'nullable|float',
            'ECG' => 'nullable|float',
            'Death' => 'nullable|string|in:DOA,Refused CPR,During intervention,During Transport',
            'TimeOfDeath' => 'nullable|time',
            'AuthorityNotified' => 'nullable|string|in:yes,no'
        ]);
        $patient->update($request->all());
        return response()->json($patient , 200);
    }



    // Delete patient Form
    public function destroy($id)
    {
        $Patient = Patient::find($id);
        if(is_null($Patient)){
            return response()->json ([
                'message' => 'No Patient Form Found'
            ] , 404);
        }
        $Patient->delete();
        return response()->json(['message' => 'Patient Form Deleted'] , 204);
    }
}
