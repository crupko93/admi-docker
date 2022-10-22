<?php

namespace App\Http\Controllers\API;

use App\Announcement;
use App\Http\Resources\AnnouncementCollection;
use App\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationAPIController extends Controller
{

    public function __construct()
    {
    }

    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function getIndex(Request $request)
    {
        // TODO: get required notifications in one db query

        // Retrieve all unread notifications for the user...
        $unreadNotifications = Notification::with('creator')
            ->where('user_id', $request->user()->id)
            ->where('read', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // Retrieve the 8 most recent read notifications for the user...
        $readNotifications = Notification::with('creator')
            ->where('user_id', $request->user()->id)
            ->where('read', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Add the read notifications to the unread notifications so they show afterwards...
        $notifications = $unreadNotifications->merge($readNotifications)->sortByDesc('created_at');

        if (count($notifications) > 0) {
            Notification::whereNotIn('id', $notifications->pluck('id'))
                ->where('user_id', $request->user()->id)
                ->where('created_at', '<', $notifications->first()->created_at)
                ->delete();
        }

        return success([
            'announcements' => AnnouncementCollection::make(
                Announcement::with('creator')->orderBy('created_at', 'desc')->get()
            ),
            'system'        => $notifications->values(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function patchMarkAsRead(Request $request)
    {
        if ($request->has('id')) {
            Notification::whereId($request->id)->update(['read' => 1]);
        } else {
            Notification::whereUserId($request->user()->id)->update(['read' => 1]);
        }

        return success([
            'announcements' => AnnouncementCollection::make(
                Announcement::with('creator')->orderBy('created_at', 'desc')->get()
            ),
            'system'        => $this->getIndex($request)['system'],
        ]);
    }

    /**
     * @param $id
     *
     * @return ResponseFactory|Response
     */
    public function deleteIndex($id)
    {
        if (empty($id)) {
            return error('Invalid data! Please recheck and try again...');
        }

        if (!Notification::findOrFail($id)->delete()) {
            return error('Could not delete the users...');
        }

        return success();
    }
}
