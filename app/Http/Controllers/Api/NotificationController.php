<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetNotificationsRequest;
use App\Http\Requests\SendFarmerOrderNotificationToStaffRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\User;
use App\Notifications\FarmerOrder;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function sendFarmerOrderNotificationToStaff(SendFarmerOrderNotificationToStaffRequest $request): JsonResponse
    {
        $farmer = FarmerDetails::find($request->input('farmer_id'));
        $staffUser = User::find($farmer->staff->user_id);

        $staffUser->notify(new FarmerOrder($request->input('order_detail'), $request->input('order')));
        return $this->success();
    }

    public function index(GetNotificationsRequest $request): JsonResponse
    {
        $authUser = $request->user('sanctum');
        $query = $authUser->notifications()->orderBy('id', 'desc');
        if ($request->filled('read') && $request->input('read') == 1) {
            $query->whereNotNull('read_at');
        }
        if ($request->filled('read') && $request->input('read') == 0) {
            $query->whereNull('read_at');
        }

        return $this->success(NotificationResource::collection($query->get()));
    }

    public function show(string $id): JsonResponse
    {
        $notification = DatabaseNotification::where('id', $id)->first();
        if (empty($notification)) {
            return $this->fail('Notification Not Found');
        }
        $notification->markAsRead();

        return $this->success(new NotificationResource($notification));
    }
}
