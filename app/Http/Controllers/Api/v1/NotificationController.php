<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    protected $notifications;
    public function  __construct(Request $request){
        $this->notifications = $request->user()->notifications();
    }
    public function index(){
        return $this->notifications->paginate(10);
    }

    public function show($notification){
        $notification = $this->notifications->findOrFail($notification);
        $notification->markAsRead();
        return response()->json(['message' => 'Marked as read']);
    }
}
