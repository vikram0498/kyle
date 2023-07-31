<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class ProfileController extends Controller
{
    public function userDetails()
    {
        // Get the authenticated user
        $user = Auth::user();
       
        $user_details = [
            'first_name' => $user->first_name ?? null,
            'last_name'  => $user->last_name ?? null,
            'name'       => $user->name ?? null,
            'email'      => $user->email ?? null,
            'phone'      => $user->phone ?? null,
            'profile_image' => $user->profile_image_url ?? null,
            'is_active'  => $user->is_active ?? 0,
            'is_block'   => $user->is_block ?? 0,
        ];
        // Return response
        $responseData = [
            'status' => true,
            'data'   => $user_details,
        ];
        return response()->json($responseData, 200);
    }

    public function updateProfile(Request $request){

        $userId = auth()->user()->id;

        $validatedData = [
            'first_name'  => 'required',
            'last_name'   => 'required',
            'phone'       => 'required',
            'profile_image'    => 'image|mimes:jpeg,jpg,png|max:1024',
        ];

        if((!is_null($request->old_password)) || $request->old_password != ''){
            $validatedData['old_password'] = [/*'required',*/ 'string','min:8'/*,new MatchOldPassword*/];
            $validatedData['new_password'] =  [/*'required',*/ 'string', 'min:8', 'different:old_password'];
            $validatedData['confirm_password'] = [/*'required',*/'min:8','same:new_password'];
        }
       
        if(!auth()->user()->email){
            $validatedData['email']  = ['required', 'string', 'email', 'max:255', Rule::unique((new User)->getTable(), 'email')->ignore($userId)->whereNull('deleted_at')];
        }

        $request->validate($validatedData,[
            'confirm_password.same' => 'The confirm password and new password must match.'
        ]);

        DB::beginTransaction();
        try {
            $updateRecords = [
                'name'  => $request->name,
                'phone' => $request->phone,
            ];

            if($request->email){
                $updateRecords['email'] = $request->email;
            }

            if($request->new_password){
                $updateRecords['password'] = Hash::make($request->new_password);
            }

            $updatedUserRecord = User::find($userId)->update($updateRecords);

            DB::commit();

            if($updatedUserRecord){
                // Start to Update Profile Image
                if($request->hasFile('profile_image')){
                    $actionType = 'save';
                    $uploadId = null;
                    if($updatedUserRecord->profileImage){
                        $uploadId = $updatedUserRecord->id;
                        $actionType = 'update';
                    }
                    uploadImage($user, $request->file('profile_image'), 'user/profile-images',"profile", 'original', $actionType, $uploadId);
                }
                // End to Update Profile Image

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
