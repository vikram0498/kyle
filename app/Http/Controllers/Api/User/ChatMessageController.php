<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        // dd($request->all());
        try{

            $validator = $request->validate([
                'buyer_id' => 'required|exists:users,id',
                'seller_id' => 'required|exists:users,id',
                'content' => 'required|string',
                'type' => 'required|in:text,image,video,file',
                'chat_type' => 'required|in:direct,group',
            ]);

            $conversationId = md5($request->buyer_id . '-' . $request->seller_id);
    
            $message = Message::create([
                'conversation_id' =>  $conversationId,
                'user_id' => auth()->user()->id,
                'content' => $request->content,
                'type' =>  $request->type,
                'chat_type' => $request->chat_type,
            ]);

            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.success_send_message'),
                'data'              => $message,
            ];
    
            return response()->json($responseData, 200);

        }catch(\Exception $e){
            // dd($e->getMessage());
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
            ];
            return response()->json($responseData, 400);
        }
        
    }

    public function getMessages(Request $request)
    {
        $conversationId = md5($request->buyer_id . '-' . $request->seller_id);
        $messages = Message::where('conversation_id', $conversationId)->orderBy('created_at', 'asc')->select('id','user_id','content','type','created_at')->with('usersSeen')->get();

        if($messages->count() > 0){
            $messages->transform(function ($message) {
                $message->date_time_lable = formatDateLabel($message->created_at);
                $message->created_date = Carbon::parse($message->created_at)->format('d-M-Y');
                $message->created_time = Carbon::parse($message->created_at)->format('g:i A');
                // $message->is_read = !is_null($message->read_at) ? true : false;
                return $message;
            });

            $responseData = [
                'status'            => true,
                'data'              => $messages,
            ];
    
            return response()->json($responseData, 200);
        }

        return response()->json(['message' => trans('messages.chat_message.no_message_found')], 404);        
    }

    public function markMessageAsSeen(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'conversation_id' => 'required',
        ]);

        $userId = auth()->user()->id; // Get the currently authenticated user's ID
        $message = Message::where('message_id', $request->message_id)->where('conversation_id', $$request->conversation_id)->first($request->message_id);

        $alreadySeen =  $message->usersSeen()->exists();

        if (!$alreadySeen) {
            // Insert a new record into the message_seen table
            $message->usersSeen()->create([
                'user_id' => $userId,
                'message_id' => $request->message_id,
                'conversation_id' => $request->conversation_id,
                'read_at' => now(), // Current timestamp
            ]);

            return response()->json(['message' => 'Message marked as seen.'], 200);
        }

        return response()->json(['message' => 'Message already seen.'], 200);
    }
}
