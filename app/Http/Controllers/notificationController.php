<?php

namespace App\Http\Controllers;

use App\Events\sendMessageEvent;
use App\Models\chat;
use App\Models\Message;
use App\Notifications\sendMessage;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class notificationController extends Controller
{

    public function sendNotification(Request $request){
     $message_info=$request->validate([
         'from'=>'required',
         'to'=>'required',
         'message'=>'required',
     ]);
     $to_id=$message_info['to'];
     $from_id=$message_info['from'];
     $message=$message_info['message'];

        $chat_id=Message::where("sender_id",$from_id)->where('receiver_id',$to_id)->select('chat_id');
        if(! Message::where(function ($query) use ($to_id,$from_id){
          $query->where('sender_id',$to_id)->where('receiver_id',$from_id);
        } )
            ->orWhere(function ($query) use ($from_id,$to_id){
                where("sender_id",$from_id)->where('receiver_id',$to_id);
            })
            ->exists())
        {
         $chatid=$chat_id;
        }
        else{
            chat::create([
                'id'=>$chat_id
            ]);
        }
        $newMessage=Message::create([
            'sender_id'=>$from_id,
            'receiver_id'=>$to_id,
            'message'=>$message
        ]);
        $receiver=User::find($to_id);
         $receiver->notify(new sendMessage($message));

         broadcast(new sendMessageEvent($newMessage));

         return response()->json([
             'your_message'=>$message,
             'alert'=>'your message sent!'
         ]);

    }
}
