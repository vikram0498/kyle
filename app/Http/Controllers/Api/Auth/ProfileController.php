<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function updateProfile(Request $request){

        $userId = auth()->user()->id;

        $request->validate([
            'name'       => 'required',
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique((new User)->getTable(), 'email')->ignore($userId)->whereNull('deleted_at')],
            'phone' => 'required',
            'old_password'     => ['required', 'string','min:8',new MatchOldPassword],
            'new_password'     => ['required', 'string', 'min:8', 'different:old_password'],
            'confirm_password' => ['required','min:8','same:new_password'],
        ],[
            'confirm_password.same' => 'The confirm password and new password must match.'
        ]);

        DB::beginTransaction();
        try {
           
            $updateRecords = [
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password'=> Hash::make($request->new_password),
            ];

            $updatedUserRecord = User::find($userId)->update($updateRecords);

            DB::commit();

            if($updatedUserRecord){
                //Return Success Response
                $responseData = [
                    'status'        => true,
                    'message'       => 'Profile has been updated',
                ];

                return response()->json($responseData, 200);
            }


        }catch (\Exception $e) {
            DB::rollBack();
             // dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }

    }
}
