<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BroadcastController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'broadcast.index']);

        return view('admin.broadcast');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
            ]);

            Notification::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
            ]);

            $notification = Notification::find($id);
            $notification->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function getNotificationData()
    {
        try {
            $notifications = Notification::query();

            return DataTables::of($notifications)
                ->addColumn('created_at', function ($notification) {
                    return \Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i:s');
                })
                ->addColumn('action', function ($notification) {
                    return '
                        <button type="button" class="btn btn-danger btn-sm" id="delete-btn" data-id="' . $notification->id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function getNotificationDataSendToUser($id)
    {
        $allNotifications = Notification::all();

        $existingNotifications = UserNotification::where('user_id', $id)
            ->where('is_removed', false)
            ->pluck('notification_id');

        foreach ($allNotifications as $notification) {
            if (!$existingNotifications->contains($notification->id)) {
                UserNotification::create([
                    'user_id' => $id,
                    'notification_id' => $notification->id,
                    'is_read' => false,
                    'read_at' => null,
                    'is_removed' => false,
                ]);
            }
        }

        $notifications = UserNotification::where('user_id', $id)
            ->where('is_read', false)
            ->where('is_removed', false)
            ->with('notification')
            ->join('notifications', 'notifications.id', '=', 'user_notifications.notification_id')
            ->orderByDesc('notifications.created_at')
            ->select('notifications.id as notification_id', 'notifications.title', 'notifications.message', 'user_notifications.read_at', 'user_notifications.is_read', 'user_notifications.id') // Select Notification fields and map to custom field names
            ->get();

        return response()->json($notifications);
    }


    public function readNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
            ]);

            $notification = UserNotification::find($validated['id']);

            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil dibaca',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ], 422);
        }
    }


}
