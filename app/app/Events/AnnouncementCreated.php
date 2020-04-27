<?php

namespace App\Events;

class AnnouncementCreated
{
    /**
     * The announcement instance.
     *
     * @var \App\Announcement
     */
    public $announcement;

    /**
     * Create a new announcement instance.
     *
     * @param  \App\Announcement  $announcement
     * @return void
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }
}
