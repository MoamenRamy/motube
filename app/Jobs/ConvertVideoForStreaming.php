<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Str;
use App\Models\Video;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $lowBitrateFormat = (new x264('aac', 'libx264'))->setKiloBitrate(500);
        $low2_BitrateFormat = (new x264('aac', 'libx264'))->setKiloBitrate(900);
        $mediumBitrateFormat = (new x264('aac', 'libx264'))->setKiloBitrate(1500);
        $highBitrateFormat = (new x264('aac', 'libx264'))->setKiloBitrate(3000);

        $convertedName_240 =     '240-' .$video->video_path;
        $convertedName_360 = '360-' .$video->video_path;
        $convertedName_480 = '480-' .$video->video_path;
        $convertedName_720 = '720-' .$video->video_path;

        FFMpeg::fromDisk($video->disc)
        ->open($video->video_path)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(426, 240));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($lowBitrateFormat)
        ->save($convertedName_240)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(640, 360));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($low2_BitrateFormat)
        ->save($convertedName_360)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(854, 480));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($mediumBitrateFormat)
        ->save($convertedName_480)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(1280, 720));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($highBitrateFormat)
        ->save($convertedName_720);

    }
}
