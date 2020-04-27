<?php

namespace App\Http\Controllers\API;

use App\Announcement;
use App\Events\AnnouncementCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Resources\{Announcement as AnnouncementResource, AnnouncementCollection, User as UserResource};
use Carbon\Carbon;
use Auth, DB;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementAPIController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Return a single announcement or a collection of all announcements
     *
     * @param Request $request
     * @param int     $announcement_id (optional) announcement ID to retrieve (else fallback to all)
     *
     * @return AnnouncementCollection|ResponseFactory|Response
     */
    public function getIndex(Request $request, $announcement_id = null)
    {
        if (empty($announcement_id)) {
            // CASE 1 - No ID given, get ALL announcements
            return AnnouncementCollection::make(
                Announcement::with('creator')
                    ->tablePaginate($request)
            );
        }

        // CASE 2 - ID given, get single announcement
        return success([
            'announcement' => AnnouncementResource::make(
                Announcement::with('creator')
                    ->whereId($announcement_id)
                    ->first()
            ),
        ]);
    }

    /**
     * Create a new announcement.
     *
     * @param AnnouncementRequest $request
     *
     * @return ResponseFactory|Response
     */
    public function postIndex(AnnouncementRequest $request)
    {
        return DB::try(function () use ($request) {
            $announcement = new Announcement([
                'user_id'     => $request->user()->id,
                'body'        => $request['body'],
                'action_text' => $request['action_text'],
                'action_url'  => $request['action_url'],
            ]);

            if (!$announcement->save()) {
                return error('Could not create announcement...');
            }

            event(new AnnouncementCreated($announcement));

            return success(['announcement' => AnnouncementResource::make($announcement)]);
        });
    }

    /**
     * Update the given announcement.
     *
     * @param AnnouncementRequest $request
     *
     * @return mixed
     */
    public function putIndex(AnnouncementRequest $request)
    {
        return DB::try(function () use ($request) {
            if (empty($request->id)) {
                return error('Invalid data! Please recheck and try again...');
            }

            $announcement = Announcement::findOrFail($request->id);
            $announcement->fill([
                'body'        => $request['body'],
                'action_text' => $request['action_text'],
                'action_url'  => $request['action_url'],
            ]);

            if (!$announcement->save()) {
                return error('Could not update announcement...');
            }

            return success(['announcement' => AnnouncementResource::make($announcement)]);
        });
    }

    /**
     * Delete announcement(s) with the given ID or ID's.
     *
     * @param $ids
     *
     * @return ResponseFactory|Response
     */
    public function deleteIndex($ids)
    {
        return DB::try(function () use ($ids) {
            $announcement_ids = explode(',', $ids);

            foreach ($announcement_ids as $id) {
                $announcement = Announcement::findOrFail($id);

                if (!$announcement->delete()) {
                    return error('Could not delete announcement!');
                }
            }

            return success();
        });
    }

    /**
     * Update the last read announcements timestamp.
     *
     * @return ResponseFactory|Response
     */
    public function patchReadAnnouncement(Request $request)
    {
        $user = Auth::user();

        if ($user === null) {
            return error('Could not find the logged user...');
        }

        $user->last_read_announcements_at = Carbon::now();

        if (!$user->save()) {
            return error('Could not mark announcements as read...');
        }

        return success(['user' => UserResource::make($user->load('roles'))]);
    }
}
