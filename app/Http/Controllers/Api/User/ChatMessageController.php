<?php

namespace App\Http\Controllers\Api\User;

use App\Events\ChatMessage;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Http;
use App\Events\NotificationSent;

class ChatMessageController extends Controller
{
    public function getChatList($recipient = null)
    {
        $userId = auth()->user()->id; 
    
        // Fetch conversations where the authenticated user is a participant
        $conversations = Conversation::where(function($query) use ($userId) {
            $query->where('participant_1', $userId)
                  ->orWhere('participant_2', $userId);
        })->get();
    
        $chatList = $conversations->map(function ($conversation) use ($userId) {
            $otherParticipantId = ($conversation->participant_1 == $userId) ? $conversation->participant_2 : $conversation->participant_1;
    
            // Fetch user details for the other participant
            $user = User::find($otherParticipantId);
    
            if (!$user) {
                return null; // Exclude invalid users
            }
    
            // Get the last message in the conversation
            $lastMessage = Message::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'desc')
                ->first();
    
            // Calculate unread message count for the authenticated user
            $unreadMessageCount = Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $userId)
                ->whereDoesntHave('seenBy', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();
    
            // Format last message details
            $lastMessageDetails = $lastMessage ? [
                'id'            => $lastMessage->id,
                'sender_id'     => $lastMessage->sender_id,
                'content'       => $lastMessage->content,
                'is_read'       => $lastMessage->seenBy()->where('user_id', $userId)->exists(),
                'created_date'  => $lastMessage->created_at->format('d-M-Y'),
                'created_time'  => $lastMessage->created_at->format('g:i A'),
                'date_time_label' => formatDateLabel($lastMessage->created_at),
            ] : null;
    
            return [
                'id'                    => $user->id,
                'name'                  => $user->name ?? '',
                'is_online'             => $user->is_online ?? '',
                'profile_image'         => $user->profile_image_url ?? null,
                'level_type'            => $user->level_type ?? '',
                'profile_tag_name'      => $user->buyerPlan ? $user->buyerPlan->title : null,
                'profile_tag_image'     => $user->buyerPlan ? $user->buyerPlan->image_url : null,
                'unread_message_count'  => $unreadMessageCount ?? 0,
                'last_message'          => $lastMessageDetails,
                'last_message_at'       => $lastMessage ? $lastMessage->created_at : null,
            ];
        })->filter();
    
        // If a recipient is provided, ensure their profile is shown even without a conversation
        if ($recipient) {
            $recipientUser = User::find($recipient);
    
            if ($recipientUser) {
                $chatList->push([
                    'id'                    => $recipientUser->id,
                    'name'                  => $recipientUser->name ?? '',
                    'is_online'             => $recipientUser->is_online ?? '',
                    'profile_image'         => $recipientUser->profile_image_url ?? null,
                    'level_type'            => $recipientUser->level_type ?? '',
                    'profile_tag_name'      => $recipientUser->buyerPlan ? $recipientUser->buyerPlan->title : null,
                    'profile_tag_image'     => $recipientUser->buyerPlan ? $recipientUser->buyerPlan->image_url : null,
                    'unread_message_count'  => 0,
                    'last_message'          => null,
                    'last_message_at'       => null,
                ]);
            }
        }
    
        // Remove duplicates and sort the list by last_message_at
        $chatList = $chatList->unique('id')->sortByDesc('last_message_at')->values();
    
        if ($chatList->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.no_record_found'),
                'data' => [],
            ], 200);
        }
    
        return response()->json([
            'status' => true,
            'data' => $chatList,
        ], 200);
    }
    
    public function sendDirectMessage(Request $request)
    {
        $request->validate([
            'recipient_id'  => 'required|exists:users,id,deleted_at,NULL',
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

            $conversation = Conversation::where('is_group', false)
            ->where(function ($query) use ($sender, $recipient_id) {
                $query->whereIn('participant_1', [$sender->id, $recipient_id])
                      ->whereIn('participant_2', [$sender->id, $recipient_id]);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'is_group'      => false,
                    'participant_1' => $sender->id,
                    'participant_2' => $recipient_id,
                    'title'         => null,
                    'created_by'    => $sender->id ,
                    'participants_count'    => 2 ,   // For Direct Messages it is 2 other than group messages
                ]);
            }

            $message = Message::create([  
                'conversation_id'   => $conversation->id,
                'sender_id'         => $sender->id,
                'receiver_id'       => $recipient_id,
                'content'           => $request->content,
                'type'              => $request->type, // text, image, video, file
                'chat_type'         => 'direct', 
            ]);
           

            $conversation->last_message_at = now();
            $conversation->save();

            $recipient = User::find($recipient_id);
            $notificationData = [
                'title'     => trans('notification_messages.chat_message.new_chat_message_from_user', ['user' => $sender->name]),
                'message'   => trans('notification_messages.chat_message.received_new_message') .'<br/> '. $request->content .'<br/>',
                'module'    => "dm_notification",
                'type'      => "dm_notification",
                'user_id'   => $recipient->id,
                'notification_type' => 'dm_notification'
            ];

            $recipient->notify(new SendNotification($notificationData));
            
            // Fire the event form mail
            event(new NotificationSent($recipient, $notificationData));

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

            $userId = auth()->user()->id;

            if ($message->conversation->participant_1 !== $userId  && $message->conversation->participant_2 !== $userId) {
                return response()->json(['error' => 'You do not have access to this message.'], 403);
            }

            $alreadyRead = DB::table('message_seen')
            ->where('message_id', $message->id)
            ->where('user_id', $userId)
            ->exists();

            if (!$alreadyRead) {
                $message->seenBy()->attach($userId, [
                    'conversation_id' => $message->conversation->id,
                    'read_at' => now(),
                ]);  
            }           

            DB::commit();

            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.marked_as_read_successfully'),
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
        $request->validate([
            'recipient_id' => 'required|exists:users,id,deleted_at,NULL', // recipient must exist
        ]);
        
        $sender = auth()->user();
        $recipient_id = $request->recipient_id;

        $recipient = User::where('id',$recipient_id)->first();

        // Find the conversation between the sender and recipient
        $conversation = Conversation::where('is_group', false)
        ->where(function ($query) use ($sender, $recipient_id) {
            $query->whereIn('participant_1', [$sender->id, $recipient_id])
                  ->whereIn('participant_2', [$sender->id, $recipient_id]);
        })->first();

        if (!$conversation) {
            return response()->json(['error' => trans('messages.chat_message.no_conversation_found')], 404);        
        }

        // Fetch all messages in this conversation
        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();        

        if($messages->count() > 0){

            $groupedMessages = $messages->transform(function ($message) {
                $message->date_time_label = formatDateLabel($message->created_at);
                $message->created_date = $message->created_at->format('d-m-Y');
                $message->created_time = $message->created_at->format('g:i A');
                $message->is_read = $message->seenBy()->exists() ? true : false;
        
                return [
                    'id'               => $message->id,
                    'sender_id'        => $message->sender_id,
                    'content'          => $message->content,
                    'created_date'     => $message->created_date,
                    'created_time'     => $message->created_time,
                    'is_read'          => $message->is_read,
                    'date_time_label'  => $message->date_time_label,
                ];
            })->groupBy(function ($message) {
                $createdDate = $message['created_date'];
                $now = Carbon::now();
        
                // Group by Today, Yesterday, Day names (for the last 7 days), or specific date
                if (Carbon::parse($createdDate)->isToday()) {
                    return 'Today';
                } elseif (Carbon::parse($createdDate)->isYesterday()) {
                    return 'Yesterday';
                } elseif (Carbon::parse($createdDate)->greaterThanOrEqualTo($now->copy()->subDays(6))) {
                    return Carbon::parse($createdDate)->format('l'); // Day name (e.g., Monday, Tuesday)
                } else {
                    return $createdDate; // Fallback to the formatted date
                }
            });
        
            $responseData = [
                'status'    => true,
                'message'   => $groupedMessages,
                'data' => [
                    'id'                => $recipient->id,
                    'name'              => $recipient->name,
                    'is_online'         => (bool)$recipient->is_online,
                    'profile_image'     => $recipient->profile_image_url ?? null,
                    'level_type'            => $recipient->level_type ?? '',
                    'profile_tag_name'      => $recipient->buyerPlan ? $recipient->buyerPlan->title : null,
                    'profile_tag_image'     => $recipient->buyerPlan ? $recipient->buyerPlan->image_url : null,
                ],
            ];
    
            return response()->json($responseData, 200);
        }

        return response()->json(['error' => trans('messages.chat_message.no_message_found')], 404);        
        
    }
}
