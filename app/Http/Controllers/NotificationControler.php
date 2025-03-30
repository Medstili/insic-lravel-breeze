<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationControler extends Controller
{
    public function markAsRead(Request $request)
    {
        $request->validate([
            'id'=>'required',
        ]);
        // Get the authenticated user.
        $user = Auth::user();
        $id = $request->id;
        // Find the notification among the unread ones.
        $notification = $user->unreadNotifications->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
}
