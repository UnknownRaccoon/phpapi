<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\ResizedPhoto;
use Cache;
use Image;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function()
        {
            $file = ResizedPhoto::whereSrc('')->orWhere('src', null)->first();
            if($file !== null) {
                $size = explode('x', $file->size);
                $path = public_path() . "/img/{$file->original->album}/{$file->original->image}";
                $image = Image::make($path);

                if($image->getWidth() > $image->getHeight()) {
                    $image->widen($size[0]);
                }
                else {
                    $image->heighten($size[1]);
                }

                $newName = "{$image->filename}_{$file->size}.{$image->extension}";
                $newPath = public_path() . "/resized/{$file->original->album}";

                if (!file_exists($newPath)) {
                    mkdir($newPath, 0777, true);
                }

                $file->src = $newName;
                $file->save();
                Cache::forget("album_{$file->original->album}");

                //Saving image caused problems during testing, has to be the last line to 100% work
                $image->save("{$newPath}/{$newName}");
            }
        })->everyMinute();
    }
}
