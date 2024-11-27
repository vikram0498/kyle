<?php

namespace App\Http\Controllers\Api\User;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;

class ChatMessageController extends Controller
{

    public function sendDirectMessage(Request $request)
    {
        $request->validate([
            'recipient_id'  => 'required|exists:users,id',
            'content'       => 'required|string|max:5000',
            'type'          => 'nullable|in:text,image,video,file',
        ]);

        try{
            DB::beginTransaction();

            $sender = auth()->user();
            $recipient_id = $request->recipient_id;

            if ($sender->id === $recipient_id) {
                return response()->json(['error' => 'You cannot send a message to yourself.'], 400);
            }

            $conversation = Conversation::where(function ($query) use ($sender, $recipient_id) {
                $query->where('is_group', false)
                      ->where(function ($query) use ($sender, $recipient_id) {
                          $query->where('sender_id', $sender->id)
                                ->where('receiver_id', $recipient_id);
                      })
                      ->orWhere(function ($query) use ($sender, $recipient_id) {
                          $query->where('receiver_id', $recipient_id)
                                ->where('sender_id', $sender->id);
                      });
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'is_group'      => false,
                    'sender_id'     => $sender->id,
                    'receiver_id'   => $recipient_id,
                    'title'         => null,
                    'created_by'    => $sender->id 
                ]);
            }

            $message = Message::create([
                'conversation_id'   => $conversation->id,
                'user_id'           => $sender->id,
                'content'           => $request->content,
                'type'              => $request->type, // text, image, video, file
                'chat_type'         => 'direct', 
            ]);
           

            $conversation->last_message_at = now();
            $conversation->save();
    
            DB::commit();

            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.success_send_message'),
                'data'              =>  $message,
            ];
            
            return response()->json($responseData, 200);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().'->'.$e->getLine()
            ];
            return response()->json($responseData, 400);
        }
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        try{
            DB::beginTransaction();
            $message = Message::find($request->message_id);

            if ($message->conversation->user_id_1 !== auth()->user()->id && $message->conversation->user_id_2 !== auth()->user()->id) {
                return response()->json(['error' => 'You do not have access to this message.'], 403);
            }

            $messageSeen = MessageSeen::updateOrCreate(
                ['user_id' => auth()->user()->id, 'message_id' => $message->id],
                ['read_at' => now()]
            );

            DB::commit();

            $responseData = [
                'status'            => true,
                'message'           => 'Message marked as read.',
            ];
            return response()->json($responseData, 200);

        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage().'->'.$e->getLine()
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
                'user_id'           => $userId,
                'message_id'        => $request->message_id,
                'conversation_id'   => $request->conversation_id,
                'read_at'           => now(),
            ]);

            return response()->json(['message' => 'Message marked as seen.'], 200);
        }

        return response()->json(['message' => 'Message already seen.'], 200);
    }
}
