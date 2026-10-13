<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    public function index()
    {
        $notifications = Auth::user()->notifications()->get();
        return $this->successMessage($notifications, 'Success', 200);
    }

    public function markAsRead(string $id)
    {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
        return $this->successMessage(null, 'Marked as read', 200);
    }


    public function ermsNotification(string $controlNo)
    {
        $notifications = DatabaseNotification::query()
            ->where('notifiable_id', $controlNo)
            ->latest()
            ->get();

        return $this->successMessage($notifications, 'Success', 200);
    }

    public function ermsMarkAsRead(Request $request, string $id)
    {
     
        DatabaseNotification::query()
            ->where('id', $id)
            ->first()
            ?->markAsRead();
            
        return $this->successMessage(null, 'Marked as read', 200);
    }
}
