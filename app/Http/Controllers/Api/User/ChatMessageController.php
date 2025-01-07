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
use App\Models\Report;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Http;
use App\Events\NotificationSent;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;


class ChatMessageController extends Controller
{
    public function getChatList(Request $request)
    {
        $request->validate([
            'recipient_id'  => 'nullable|exists:users,id',
            'is_blocked'    => 'nullable|in:0,1',
        ]);
        
        $recipient = $request->recipient_id ?? null; 
        $isBlocked = (bool)$request->is_blocked ?? false;
        
        $authUser = auth()->user(); 
        
        // Fetch wishlist users
        $wishlistUsers = User::whereIn('id', function ($query) use ($authUser) {
            $query->select('wishlist_user_id')
                ->from('wishlists')
                ->where('user_id', $authUser->id);
        });
        
        if ($request->filled('is_blocked')) {
            $wishlistUsers->whereIn('id', function ($query) use ($authUser,$isBlocked) {
                $query->select('user_id')
                    ->from('user_block')
                    ->where('blocked_by', $authUser->id)
                    ->where('block_status', $isBlocked);
            });
        }
        
        $wishlistUsers = $wishlistUsers->get();

        // Fetch conversations where the authenticated user is a participant
        $conversations = Conversation::where(function ($query) use ($authUser) {
            $query->where('participant_1', $authUser->id)
                ->orWhere('participant_2', $authUser->id);
        })->get();

        $chatList = $conversations->map(function ($conversation) use ($authUser, $request, $isBlocked) {
            $otherParticipantId = ($conversation->participant_1 == $authUser->id) ? $conversation->participant_2 : $conversation->participant_1;

            // Fetch user details for the other participant
            $userQuery = User::where('users.id', $otherParticipantId)
            ->leftJoin('user_block', function ($join) use ($authUser) {
                $join->on('users.id', '=', 'user_block.user_id')
                     ->where('user_block.blocked_by', $authUser->id);
            })
            ->where(function ($query) use ($isBlocked) {
                if ($isBlocked) {
                    $query->where('user_block.block_status', 1);
                } else {
                    $query->whereNull('user_block.block_status')
                          ->orWhere('user_block.block_status', 0);
                }
            });
            
            $user = $userQuery->select('users.*')->first();

            if (!$user) {
                return null; // Exclude invalid users
            }

            // Get the last message in the conversation
            $lastMessage = Message::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Calculate unread message count for the authenticated user
            $unreadMessageCount = Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $authUser)
                ->whereDoesntHave('seenBy', function ($query) use ($authUser) {
                    $query->where('user_id', $authUser->id);
                })
                ->where(function ($query) use ($authUser) {
                    $query->whereDoesntHave('sender.blockedUsers', function ($blockQuery) use ($authUser) {
                        $blockQuery->where('blocked_by', $authUser->id)
                            ->where('block_status', 1);
                    });
                })
                ->count();

            // Format last message details
            $lastMessageDetails = $lastMessage ? [
                'id'             => $lastMessage->id,
                'sender_id'      => $lastMessage->sender_id,
                'content'        => $lastMessage->content,
                'is_read'        => $lastMessage->seenBy()->where('user_id', $authUser->id)->exists(),
                'created_date'   => $lastMessage->created_at->format('d-M-Y'),
                'created_time'   => $lastMessage->created_at->format('g:i A'),
                'date_time_label'=> formatDateLabel($lastMessage->created_at),
            ] : null;

            return [
                'id'                    => $user->id,
                'conversation_uuid'     => $conversation ? $conversation->uuid : null,
                'name'                  => $user->name ?? '',
                'is_online'             => $user->is_online ?? '',
                'is_block'              => $user->isBlockedByAuthUser(),
                'profile_image'         => $user->profile_image_url ?? null,
                'level_type'            => $user->level_type ?? '',
                'profile_tag_name'      => $user->buyerPlan ? $user->buyerPlan->title : null,
                'profile_tag_image'     => $user->buyerPlan ? $user->buyerPlan->image_url : null,
                'unread_message_count'  => $unreadMessageCount ?? 0,
                'last_message'          => $lastMessageDetails,
                'last_message_at'       => $lastMessage ? $lastMessage->created_at->format('d-M-Y g:i A') : null,
                'wishlisted'            => $authUser->wishlistedUsers()->where('wishlist_user_id',$user->id)->exists(),
            ];
        })->filter();

        // Add wishlist users who are not already in the chat list
        $wishlistUsers->each(function ($wishlistUser) use ($chatList) {
            if (!$chatList->pluck('id')->contains($wishlistUser->id)) {
                $chatList->push([
                    'id'                    => $wishlistUser->id,
                    'conversation_uuid'     => null,
                    'name'                  => $wishlistUser->name ?? '',
                    'is_block'              => $wishlistUser->isBlockedByAuthUser(),
                    'is_online'             => $wishlistUser->is_online ?? '',
                    'profile_image'         => $wishlistUser->profile_image_url ?? null,
                    'level_type'            => $wishlistUser->level_type ?? '',
                    'profile_tag_name'      => $wishlistUser->buyerPlan ? $wishlistUser->buyerPlan->title : null,
                    'profile_tag_image'     => $wishlistUser->buyerPlan ? $wishlistUser->buyerPlan->image_url : null,
                    'unread_message_count'  => 0,
                    'last_message'          => null,
                    'last_message_at'       => null,
                    'wishlisted'            => true,
                ]);
            }
        });

        // If a recipient is provided, ensure their profile is shown even without a conversation
        if ($recipient) {
            $recipientUser = User::find($recipient);

             $isBlockStatus = $recipientUser->isBlockedByAuthUser();

             if ($recipientUser && (is_null($isBlocked) ||  ($isBlockStatus == $isBlocked) )) {
                $chatList->push([
                    'id'                    => $recipientUser->id,
                    'conversation_uuid'     => null,
                    'name'                  => $recipientUser->name ?? '',
                    'is_online'             => $recipientUser->is_online ?? '',
                    'is_block'              => $isBlockStatus,
                    'profile_image'         => $recipientUser->profile_image_url ?? null,
                    'level_type'            => $recipientUser->level_type ?? '',
                    'profile_tag_name'      => $recipientUser->buyerPlan ? $recipientUser->buyerPlan->title : null,
                    'profile_tag_image'     => $recipientUser->buyerPlan ? $recipientUser->buyerPlan->image_url : null,
                    'unread_message_count'  => 0,
                    'last_message'          => null,
                    'last_message_at'       => null,
                    'wishlisted'            => $authUser->wishlistedUsers()->where('wishlist_user_id',$recipientUser->id)->exists(),
                ]);
            }
        }

        // Sort chat list: Wishlist users first, then by last message timestamp
        $chatList = $chatList
            ->unique('id')
            ->sortByDesc(function ($item) use ($wishlistUsers,$recipient) {
                return [
                    $item['id'] == $recipient ? 2 : 0, // Recipient priority (highest priority)
                    $wishlistUsers->pluck('id')->contains($item['id']) ? 1 : 0, // Wishlist priority
                    $item['last_message_at'], // Sort by last message
                ];
            })->values();

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

            // Calculate unread message count for the authenticated user
            $unreadMessageCount = Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $recipient_id)
                ->whereDoesntHave('seenBy', function ($query) use ($recipient_id) {
                    $query->where('user_id', $recipient_id);
                })
                ->count();
                
            $conversation->last_message_at = now();
            $conversation->save();

            $recipient = User::find($recipient_id);
            $isBlocked = $sender->blockedUsers()->where('blocked_by', $recipient_id)->where('block_status', 1)->exists();

            if(!$isBlocked){
                
                $notificationData = [
                    'title'     => trans('notification_messages.chat_message.new_chat_message_from_user', ['user' => $sender->name]),
                    'message'   => trans('notification_messages.chat_message.received_new_message') .' '. $request->content,
                    'module'    => "dm_notification",
                    'type'      => "dm_notification",
                    'user_id'   => $recipient->id,
                    'notification_type' => 'dm_notification',
                    'conversation_uuid'   => $conversation->uuid,
                    'sender_id'         => $sender->id,
                ];
    
                $recipient->notify(new SendNotification($notificationData));
                
                // Fire the event form mail
                event(new NotificationSent($recipient, $notificationData));
            }
            
            $cacheKey = "conversation_messages_{$conversation->id}";
            Cache::forget($cacheKey);

            DB::commit();

            $data = $message->toArray();
            $data['conversation_uuid'] = $conversation->uuid;

            $data['sender_user'] = [
                'id'                    => $sender->id,
                'name'                  => $sender->name ?? '',
                'is_online'             => $sender->is_online ?? '',
                'is_block'              => $isBlocked,
                'profile_image'         => $sender->profile_image_url ?? null,
                'level_type'            => $sender->level_type ?? '',
                'profile_tag_name'      => $sender->buyerPlan ? $sender->buyerPlan->title : null,
                'profile_tag_image'     => $sender->buyerPlan ? $sender->buyerPlan->image_url : null,
                'unread_message_count'  => $unreadMessageCount,
                'last_message'          => [
                    'id'             => $message->id,
                    'sender_id'      => $message->sender_id,
                    'content'        => $message->content,
                    'is_read'        => $message->seenBy()->where('user_id', $recipient->id)->exists(),
                    'created_date'   => $message->created_at->format('d-M-Y'),
                    'created_time'   => $message->created_at->format('g:i A'),
                    'date_time_label'=> formatDateLabel($message->created_at),
                ],
                'last_message_at'    => $message ? $message->created_at->format('d-M-Y g:i A') : null,
                'wishlisted'         => $recipient->wishlistedUsers()->where('wishlist_user_id',$sender->id)->exists(),
            ];

           
            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.success_send_message'),
                'data'              =>  $data,
                'unread_message_count' => $unreadMessageCount
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
        $isBlocked = 0;
        $blockTimestamp = '';
        
        $recipient = User::where('id',$recipient_id)->first();

        $userBlocked = $sender->blockedUsers()->where('blocked_by', $recipient->id)->where('block_status', 1)->first();
    
        if ($userBlocked) {
             $pivotData = $userBlocked->pivot;
            if (isset($pivotData)) {
                $isBlocked = $pivotData->block_status;
                $blockTimestamp = $pivotData->blocked_at;
            }
        }
        
        // \Log::info($userBlocked);
        
        // \Log::info('$blockTimestamp-'.$blockTimestamp);
        
     
        // Find the conversation between the sender and recipient
        $conversation = Conversation::where('is_group', false)
        ->where(function ($query) use ($sender, $recipient_id) {
            $query->whereIn('participant_1', [$sender->id, $recipient_id])
                  ->whereIn('participant_2', [$sender->id, $recipient_id]);
        })->first();

        if (!$conversation) {
            $responseData = [
                'status'    => true,
                'message'   => [],
                'data' => [
                    'id'                => $recipient->id,
                    'name'              => $recipient->name,
                    'is_online'         => (bool)$recipient->is_online,
                    'is_block'          => $recipient->isBlockedByAuthUser(), 
                    'wishlisted'        => $sender->wishlistedUsers()->where('wishlist_user_id',$recipient->id)->exists(),
                    'profile_image'     => $recipient->profile_image_url ?? null,
                    'level_type'            => $recipient->level_type ?? '',
                    'profile_tag_name'      => $recipient->buyerPlan ? $recipient->buyerPlan->title : null,
                    'profile_tag_image'     => $recipient->buyerPlan ? $recipient->buyerPlan->image_url : null,
                ],
            ];
    
            return response()->json($responseData, 200); 
        }
        
        //Mark as notification read
        // $sender->notification()->where('notification_type','dm_notification')->whereNull('read_at')->update(['read_at' => now()]);

        // Fetch all messages (no cache expiration time needed)
        $cacheKey = "conversation_messages_{$conversation->id}";
        $messages = Cache::rememberForever($cacheKey, function () use ($conversation,$isBlocked,$blockTimestamp) {
            $query = Message::where('conversation_id', $conversation->id);

            if ($isBlocked && $blockTimestamp) {
                $query->where('created_at', '<=', $blockTimestamp);
            }
    
            return $query->orderBy('created_at', 'asc')->get();
        });

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
                    'conversation_uuid' => $conversation->uuid,
                    'name'              => $recipient->name,
                    'is_online'         => (bool)$recipient->is_online,
                    'is_block'          => $recipient->isBlockedByAuthUser(),
                    'wishlisted'        => $sender->wishlistedUsers()->where('wishlist_user_id',$recipient->id)->exists(),
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

    public function markAsRead(Request $request)
    {
        $request->validate([
            'conversationUuid' => 'required|exists:conversations,uuid',
        ]);

        try{
            DB::beginTransaction();
            
            $userId = auth()->user()->id;

            $conversation = Conversation::where('uuid',$request->conversationUuid)->first();
        
            $messages = $conversation->messages()->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('message_seen')
                    ->whereRaw('message_seen.message_id = messages.id')
                    ->where('message_seen.user_id', $userId);
            })->get();

            if($messages->count() > 0){

                foreach($messages as $message){
                    if ($message->conversation->participant_1 !== $userId  && $message->conversation->participant_2 !== $userId) {
                        return response()->json(['error' => 'You do not have access to this message.'], 403);
                    }
    
                    $message->seenBy()->attach($userId, [
                        'conversation_id' => $message->conversation->id,
                        'read_at' => now(),
                    ]);  
                    
                }
            }

            // Mark notification as read

            Notification::where('notification_type','dm_notification')
            ->whereNull('read_at')
            ->whereJsonContains('data->user_id', $userId)
            ->whereJsonContains('data->conversation_uuid', $conversation->uuid)
            ->update(['read_at' => now()]);
                       
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

    public function updateBlockStatus(Request $request){
        $request->validate([
            'recipient_id'      => 'required|exists:users,id',
        ]);

        try{
            DB::beginTransaction();
            $user = User::find($request->recipient_id);
            if($user){
            
                if ($user->trashed()) {
                    return response()->json([
                        'status' => false,
                        'error'  => trans('messages.chat_message.user_trashed_error')
                    ], 400);
                }

                $blockStatus = $this->toggleBlock($user);

                DB::commit();
                $responseData = [
                    'status'            => true,
                    'blockStatus'       => $blockStatus,
                    'message'           => $blockStatus ? trans('messages.chat_message.user_block_success') : trans('messages.chat_message.user_unblock_success'),
                ];
                return response()->json($responseData, 200);
            }

            return response()->json(['status'=>false, 'error' => trans('messages.chat_message.no_message_found')], 404);  

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

    public function addToWishlist(Request $request){
        $request->validate([
            'wishlist_user_id' => 'required|exists:users,id',
        ]);

        try{
            DB::beginTransaction();
            $authUser = auth()->user();

            if ($authUser->id == $request->wishlist_user_id) {
                $responseData = [
                    'status'            => false,
                    'message'           => trans('messages.chat_message.yourself_not_to_add_wishlist'),
                ];
                return response()->json($responseData, 400);
            }

            if ($authUser->wishlistedUsers()->where('wishlist_user_id', $request->wishlist_user_id)->exists()) {
                $responseData = [
                    'status'            => false,
                    'message'           => trans('messages.chat_message.already_added_wishlist'),
                ];
                return response()->json($responseData, 400);
            }

            $authUser->wishlistedUsers()->attach($request->wishlist_user_id);

            DB::commit();

            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.added_wishlist_success'),
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

    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'wishlist_user_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();
            $authUser = auth()->user();

            if (!$authUser->wishlistedUsers()->where('wishlist_user_id', $request->wishlist_user_id)->exists()) {
                $responseData = [
                    'status'  => false,
                    'message' => trans('messages.chat_message.not_in_wishlist'),
                ];
                return response()->json($responseData, 400);
            }

            $authUser->wishlistedUsers()->detach($request->wishlist_user_id);

            DB::commit();

            $responseData = [
                'status'  => true,
                'message' => trans('messages.chat_message.removed_wishlist_success'),
            ];
            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            DB::rollBack();

            $responseData = [
                'status'        => false,
                'error'         => trans('messages.error_message'),
                'error_details' => $e->getMessage() . '->' . $e->getLine(),
            ];
            return response()->json($responseData, 400);
        }
    }

    public function addToReport(Request $request,$conversationUuid){
        $request->validate([
            'reason'  => 'required|exists:reasons,id',
            'comment' => 'nullable|string|max:255',
        ]);

        try{
            DB::beginTransaction();
            $authUser = auth()->user();

            $conversation = Conversation::where('uuid',$conversationUuid)->first();
            if (!$conversation) {
                return response()->json(['error' => 'Conversation not found'], 404);
            }

            // Check if the user is part of the conversation
            if (!in_array($authUser->id, [$conversation->participant_1, $conversation->participant_2])) {
                return response()->json(['error' => 'Unauthorized action'], 403);
            }
            
            // Check if the conversation is already reported by the user
            /*$existingReport = Report::where('conversation_id', $conversation->id)
                ->where('reported_by', $authUser->id)
                ->first();

            if ($existingReport) {
                return response()->json(['message' => trans('messages.chat_message.conversation_already_reported')], 200);
            }*/

            // Add the conversation to the report
            $report = Report::create([
                'conversation_id' => $conversation->id,
                'reason' => $request->reason ?? null,
                'comment' => $request->comment ?? null,
            ]);

            DB::commit();

            $responseData = [
                'status'            => true,
                'message'           => trans('messages.chat_message.conversation_added_to_report'),
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

   
    private function toggleBlock($user)
    {
        $authUser = auth()->user();
        $existingBlock =  $user->blockedUsers()->where('blocked_by', $authUser->id)->first();

        if ($existingBlock) {
            $existingBlock->pivot->block_status = !$existingBlock->pivot->block_status;
            
            if ($existingBlock->pivot->block_status == 0) {
                $existingBlock->pivot->blocked_at = null;
            } else {
                $existingBlock->pivot->blocked_at = now();
            }
        
            $existingBlock->pivot->save();

            return $existingBlock->pivot->block_status;
        } else {
            $user->blockedUsers()->attach($authUser->id, ['block_status' => 1, 'blocked_at' => now()]);
            return true; // Blocked
        }
    }
    

}
