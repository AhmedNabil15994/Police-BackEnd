<?php

namespace Modules\Notification\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Notification\Http\Requests\Dashboard\NotificationRequest;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as Notification;
use Modules\Notification\Traits\SendNotificationTrait as SendNotification;

class NotificationController extends Controller
{
    use SendNotification;

    protected $notification;

    function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function notifyForm()
    {
        return view('notification::dashboard.create');
    }

    public function push_notification(NotificationRequest $request)
    {
        try {
            $tokens = $this->notification->getAllTokens();

            if (count($tokens) > 0) {
                $data = [
                    'title' => $request['title'],
                    'body' => $request['body'],
                    'type' => 'general',
                    'id' => null,
                ];

                $this->send($data, $tokens);

                return Response()->json([true, __('notification::dashboard.notifications.general.message_sent_success')]);
            } else
                return Response()->json([false, __('notification::dashboard.notifications.general.no_tokens')]);

        } catch (\Exception $e) {
            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        }

    }

}
