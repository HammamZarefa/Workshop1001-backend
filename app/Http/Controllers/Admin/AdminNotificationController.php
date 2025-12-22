<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdminNotificationController extends Controller
{

    public function create()
    {
        return view('admin.notifications.create', [
            'users'     => User::where('is_active', true)->get(),
            'templates' => NotificationTemplate::all(),
        ]);
    }


     // Send notification (broadcast or targeted)

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'message'     => 'required|string',
            'channels'    => 'required|array',
            'channels.*'  => 'in:database,mail',
            'users'       => 'nullable|array',
            'users.*'     => 'exists:users,id',
        ]);

        DB::transaction(function () use ($data) {

            $recipients = empty($data['users'])
                ? User::where('is_active', true)->get()   // Broadcast
                : User::whereIn('id', $data['users'])->get();

            Notification::send(
                $recipients,
                new SystemNotification(
                    $data['title'],
                    $data['message'],
                    $data['channels']
                )
            );

            NotificationLog::create([
                'admin_id' => auth()->id(),
                'title'    => $data['title'],
                'message'  => $data['message'],
                'channels' => $data['channels'],
                'sent_to'  => $recipients->count(),
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Notification sent successfully');
    }


    public function index()
    {
        return view('admin.notifications.index', [
            'logs' => NotificationLog::latest()->paginate(20),
        ]);
    }
}
