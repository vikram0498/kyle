<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportMail;

class SupportController extends Controller
{
    public function getContactPreferance(){
        $elementValues['contact_preferances'] = collect(config('constants.contact_preferances'))->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => ucwords(strtolower($label)),
            ];
        })->values()->all();

        //Return Error Response
        $responseData = [
            'status'        => true,
            'result'        => $elementValues,
        ];
        return response()->json($responseData, 200);
    }

    public function support(SupportRequest $request){
        try{
            
            DB::beginTransaction();
            $validatedData['name'] = $request->name;
            $validatedData['email'] = $request->email;
            $validatedData['phone_number'] = $request->phone_number;
            $validatedData['contact_preferance'] = $request->contact_preferance;
            $validatedData['message'] = $request->message;
            $createdBuyer = Support::create($validatedData);

            $subject = 'User Support Mail';
            $email_id = config('constants.owner_email');
            Mail::to($email_id)->queue(new SupportMail($validatedData, $subject));

            $responseData = [
                'status'        => true,
                'message'       => "Request submitted successfully",
            ];
            DB::commit();
            return response()->json($responseData, 200);
        }catch(\Exception $e){
            
            DB::rollBack();
            $responseData = [
                'status'        => false,
                // 'error'         => $e->getMessage().$e->getLine(),
                'error' => 'Something went wrong!',
            ];
            return response()->json($responseData, 400);
        }
    }
}
