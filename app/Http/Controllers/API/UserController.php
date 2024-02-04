<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //return all users
    public function index(Request $request)
    {
        $users = User::all();
        $user = $users->map(function ($user) {
            return [
            'id' => $user->id,
            'Name' => $user->Name,
            'Email' => $user->Email,
            'PersonalID' => $user->PersonalID,
            'Password' => $user->getOriginalPassword(),
            'DateOfBirth' => $user->DateOfBirth,
            'Type' => $user->Type,
            'inService' => $user->inService,
            ];
        });
        return response()->json($user->all());
    }

    //create new user
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string',
            'Email' => 'required|string',
            'PersonalID' => 'nullable|string',
            'Password' => 'required|string',
            'DateOfBirth' => 'nullable|date',
            'Type' => 'required|string|in:Admin,Paramedic,Hospital',
            'inService' => 'required|string|in:Active,NotActive',
        ]);

        try {
            $user = User::create($request->all());
            return response()->json(['message' => 'User Created Successfully','user' => $user], 201);

        // Code that may throw exceptions (database queries)
        } catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            //Error code 1062 in MySQL indicates a duplicate entry violation. This occurs when trying to insert a record with a unique constraint
            if ($errorCode == 1062)
             {
                return response()->json(['message' => 'Email Already Exists'], 409);
            }
            return response()->json(['message' => 'Error Creating User'],500);
        }
    }

    //return a specific user info
    public function show($Name)
    {
        // Use the SQL LIKE operator to search for partial matches in the 'Name' column
        $users = User::where('Name', 'like', '%' . $Name . '%')->get();
        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No User Found'
            ], 404);
        }
        $decryptedUsers = $users->map(function ($user) {
            // Use the getOriginalPassword() method to decrypt the password
            $decryptedPassword = $user->getOriginalPassword();
            return [
                'id' => $user->id,
                'Name' => $user->Name,
                'Email' => $user->Email,
                'PersonalID' => $user->PersonalID,
                'Password' => $decryptedPassword,
                'DateOfBirth' => $user->DateOfBirth,
                'Type' => $user->Type,
                'inService' => $user->inService,
            ];
        });
        return response()->json($decryptedUsers);
    }

    //update user info
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json ([
                'message' => 'No User Found'
            ] , 404);
        }

        $request->validate([
            'Name' => 'sometimes|required|string',
            'Email' => 'sometimes|required|string',
            'PersonalID' => 'sometimes|string',
            'Password' => 'sometimes|required|string',
            'DateOfBirth' => 'sometimes|nullable|date|date_format:Y-m-d',
            'Type' => 'sometimes|required|string|in:Admin,Paramedic,Hospital',
            'inService' => 'sometimes|required|string|in:Active,NotActive',
        ]);

        try {
        $user->update($request->all());
            return response()->json(['message' => 'User Updated Successfully','user' => $user], 201);

        } catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062)
             {
                return response()->json(['message' => 'Email Already Exists'], 409);
            }
            return response()->json(['message' => 'Error Updating User'],500);
        }
}


    //delete user
    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'No User Found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User Deleted'] , 204);
    }
}
