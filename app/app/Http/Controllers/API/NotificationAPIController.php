<?php

namespace App\Http\Controllers\API;

use App\Notification;
use App\Http\Controllers\Controller;
use App\Services\{AnnouncementService, NotificationService};
use Illuminate\Http\Request;

class NotificationAPIController extends Controller
{
    protected AnnouncementService $announcement;

    protected NotificationService $notification;

    public function __construct(AnnouncementService $announcement, NotificationService $notification)
    {
        $this->announcement = $announcement;
        $this->notification = $notification;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getIndex(Request $request)
    {
        return success([
            'announcements' => $this->announcement->all(),
            'system'        => $this->notification->all($request->user()),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function patchMarkAsRead(Request $request)
    {
        if ($request->has('id')) {
            Notification::whereId($request->id)->update(['read' => 1]);
        } else {
            Notification::whereUserId($request->user()->id)->update(['read' => 1]);
        }

        return success([
            'announcements' => $this->announcement->all(),
            'system'        => $this->notification->all($request->user()),
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteIndex($id)
    {
        return $this->notification->delete($id);
    }
}
