<?php

namespace App\Http\Controllers\API;

use App\Announcement;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Resources\{Announcement as AnnouncementResource, AnnouncementCollection};
use App\Services\AnnouncementService;
use DB;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementAPIController extends Controller
{
    protected AnnouncementService $announcement;

    public function __construct(AnnouncementService $announcement)
    {
        $this->announcement = $announcement;

        $this->middleware('role:executive_one');
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
            return $this->announcement->create($request->user(), $request->all());
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
            return $this->announcement->update($request->id, $request->all());
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
            return $this->announcement->delete($ids);
        });
    }
}
