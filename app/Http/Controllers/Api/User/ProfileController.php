<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Buyer;
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
            'first_name'          => $user->first_name ?? null,
            'last_name'           => $user->last_name ?? null,
            'name'                => $user->name ?? null,
            'email'               => $user->email ?? null,
            'country_code'        => $user->country_code ?? null,
            'phone'               => $user->phone ?? null,
            'full_phone_number'   => $user->full_phone_number ?? null,
            'profile_image'       => $user->profile_image_url ?? null,
            'level_type'          => $user->level_type ?? null,
            'is_active'           => $user->is_active ?? 0,
            'is_block'            => $user->is_block ?? 0,
            'credit_limit'        => $user->credit_limit ?? 0,
        ];
        // Return response
        $responseData = [
            'status' => true,
            'data'   => $user_details,
        ];
        return response()->json($responseData, 200);
    }

    public function updateProfile(Request $request){

        $authUser = auth()->user();

        $validatedData = [
            'first_name'  => 'required',
            'last_name'   => 'required',
        ];
        $validatedData['country_code'] = ['required', 'numeric'];
        
        /*/$validatedData['phone'] = [
            'required', 'numeric','digits:10','not_in:-',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('country_code', $request->country_code);
            })->ignore($authUser->id, 'id')
        ];*/

        if($request->old_password || $request->new_password || $request->confirm_password){
            $validatedData['old_password'] = ['required', 'string','min:8',new MatchOldPassword];
            $validatedData['new_password'] =  ['required', 'string', 'min:8', 'different:old_password'];
            $validatedData['confirm_password'] = ['required','min:8','same:new_password'];
        }
       
        if(!auth()->user()->email){
            $validatedData['email']  = ['required', 'string', 'email', 'max:255', Rule::unique((new User)->getTable(), 'email')->ignore($authUser->id)];
        }

        if($request->hasFile('profile_image')){
            $validatedData['profile_image'] = ['image','mimes:jpeg,jpg,png','max:'.config('constants.profile_image_size')];
        }

        $request->validate($validatedData,[
            'phone.required' => 'The phone number field is required',
            'phone.digits'   => 'The mobile number must be 10 digits',
            'phone.unique'   => 'The mobile number already exists.',
            'confirm_password.same' => 'The confirm password and new password must match.',
        ]);

        try {
            DB::beginTransaction();

            /*if(($request->country_code != $request->country_code) || ($authUser->phone != $request->phone)){
                //Start to check phone number verified
                if(!isPhoneNumberVerified($request->country_code,$request->phone)){
                    $responseData = [
                        'status'        => false,
                        'message'       => 'OTP not verified.',
                    ]; 
                    return response()->json($responseData, 403);
                }
                //End to check phone number verified
            }*/

            $updateRecords = [
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'name'          => $request->first_name.' '.$request->last_name ,
               /* 'country_code'  => $request->country_code,
                'phone'         => $request->phone,*/
            ];

            if($request->email){
                $updateRecords['email'] = $request->email;
            }

            if($request->new_password){
                $updateRecords['password'] = Hash::make($request->new_password);
            }

            $updatedUserRecord = User::find($authUser->id)->update($updateRecords);

            //Clear OTP Cache
            forgetOtpCache($request->country_code,$request->phone);

            DB::commit();

            if($updatedUserRecord){
                // Start to Update Profile Image
                if($request->hasFile('profile_image')){
                   
                    $actionType = 'save';
                    $uploadId = null;
                    $updatedUser= User::find($authUser->id);
                    if($updatedUser->profileImage){
                        $uploadId = $updatedUser->profileImage->id;
                        $actionType = 'update';
                    }
                    uploadImage($updatedUser, $request->file('profile_image'), 'user/profile-images',"profile", 'original', $actionType, $uploadId);
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
            //  dd($e->getMessage().'->'.$e->getLine());
            
            //Return Error Response
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }

    }

    public function getCurrentLimit(){
         // Return response
         $responseData = [
            'status' => true,
            'total_buyer_uploaded'   => auth()->user()->buyers()->where('user_id','!=',auth()->user()->id)->count(),
            'credit_limit'   => auth()->user()->credit_limit,
            'is_active' => auth()->user()->is_active ? true : false,
        ];
        return response()->json($responseData, 200);
    }
    
    public function updateBuyerProfileImage(Request $request){
        $user = auth()->user();

        $rules['profile_image'] = ['required','image','mimes:jpeg,jpg,png','max:'.config('constants.profile_image_size')];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if($user->buyerDetail->is_profile_verified){
              
                // Start to Update Profile Image
                if($request->hasFile('profile_image')){
                    $actionType = 'save';
                    $uploadId = null;
                    if($user->profileImage){
                        $uploadId = $user->profileImage->id;
                        $actionType = 'update';
                    }
                    uploadImage($user, $request->file('profile_image'), 'user/profile-images',"profile", 'original', $actionType, $uploadId);
                }
                // End to Update Profile Image
                $user->save();
                DB::commit();
                //Return Success Response
                $buyer = User::find($user->id);
                $responseData = [
                    'status'        => true,
                    'profile_image' => $buyer->profile_image_url ?? null,
                    'message'       => trans('messages.auth.verification.profile_upload_success'),
                ];
                return response()->json($responseData, 200);
            }else{
                //Return Error Response
                $responseData = [
                    'status'        => false,
                    'error'         => trans('messages.auth.buyer.verify_profile'),
                ];
                return response()->json($responseData, 400);
            }

            DB::commit();
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

    public function updateBuyerSearchStatus(Request $request){
        $rules['status'] = ['required','numeric','in:0,1'];
        $request->validate($rules);

        $userId = auth()->user()->id;

        Buyer::where('buyer_user_id',$userId)->update(['status'=>$request->status]);

        // Return response
        $responseData = [
            'status' => true,
            'message'   => 'Status Updated Successfully!',
        ];
        return response()->json($responseData, 200);
        
    }

    public function updateBuyerContactPreference(Request $request){
        $rules['contact_preference'] = ['required','numeric','in:'.implode(',', array_keys(config('constants.contact_preferances')))];

        $request->validate($rules);

        $userId = auth()->user()->id;

        Buyer::where('buyer_user_id',$userId)->update(['contact_preferance'=>$request->contact_preference]);

        // Return response
        $responseData = [
            'status' => true,
            'message'   => 'Updated Successfully!',
        ];
        return response()->json($responseData, 200);
        
    }

    public function updateUserRole(Request $request){

        try {
            DB::beginTransaction();

            $authUser = auth()->user();

            if(!($authUser->level_type == 2 || $authUser->level_type == 3)){
                $responseData = [
                    'status'    => false,
                    'message'   => "Your account should level 2 type user!",
                ];
                return response()->json($responseData, 403);
            }

            // Define role IDs for seller and buyer
            $sellerRoleId = config('constants.roles.seller');
            $buyerRoleId = config('constants.roles.buyer');

            // Determine the target role based on the user's current role
            $targetRoleId = $authUser->roles()->wherePivot('role_id', $sellerRoleId)->exists() 
                ? $buyerRoleId 
                : $sellerRoleId;

            // Detach both roles to ensure only one role is active
            $authUser->roles()->detach([$sellerRoleId, $buyerRoleId]);

            // Attach the target role
            $authUser->roles()->attach($targetRoleId);

            if($targetRoleId == $buyerRoleId){
                $authUser->is_switch_role = 1;
            }else{
                $authUser->is_switch_role = 0;
            }

            $authUser->save();
           
            DB::commit();

            $responseData = [
                'status'    => true,
                'message'   => "Role Updated Successfully!",
                'userData'          => [
                    'id'           => $authUser->id,
                    'first_name'   => $authUser->first_name ?? '',
                    'last_name'    => $authUser->last_name ?? '',
                    'profile_image'=> $authUser->profile_image_url ?? '',
                    'role'=> $authUser->roles()->first()->id ?? '',
                    'level_type'   => $authUser->level_type,
                    'credit_limit' => $authUser->credit_limit,
                    'is_verified'  => $authUser->is_buyer_verified,
                    'is_switch_role' => $authUser->is_switch_role,
                    'total_buyer_uploaded' => $authUser->buyers()->count(),
                ],
            ];
            return response()->json($responseData, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $th->getMessage().'->'.$th->getLine()
            ];
            return response()->json($responseData, 400);
        }

    }
    
}
