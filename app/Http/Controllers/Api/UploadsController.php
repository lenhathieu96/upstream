<?php

namespace App\Http\Controllers\Api;

use App\Models\Uploads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Uploads $uploads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uploads $uploads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uploads $uploads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uploads $uploads)
    {
        //
    }


    public function upload_photo($file,$user_id, $user_type)
    {
        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
            "mp4" => "video",
            "mpg" => "video",
            "mpeg" => "video",
            "webm" => "video",
            "ogg" => "video",
            "avi" => "video",
            "mov" => "video",
            "flv" => "video",
            "swf" => "video",
            "mkv" => "video",
            "wmv" => "video",
            "wma" => "audio",
            "aac" => "audio",
            "wav" => "audio",
            "mp3" => "audio",
            "zip" => "archive",
            "rar" => "archive",
            "7z" => "archive",
            "doc" => "document",
            "txt" => "document",
            "docx" => "document",
            "pdf" => "document",
            "csv" => "document",
            "xml" => "document",
            "ods" => "document",
            "xlr" => "document",
            "xls" => "document",
            "xlsx" => "document"
        );

        if (!empty($file)) {
            $upload = new Uploads;
            $extension = strtolower($file->getClientOriginalExtension());

            // if (
            //     env('DEMO_MODE') == 'On' &&
            //     isset($type[$extension]) &&
            //     $type[$extension] == 'archive'
            // ) {
            //     return '{}';
            // }

            if (isset($type[$extension])) {
                $upload->file_original_name = null;
                $arr = explode('.', $file->getClientOriginalName());
                for ($i = 0; $i < count($arr) - 1; $i++) {
                    if ($i == 0) {
                        $upload->file_original_name .= $arr[$i];
                    } else {
                        $upload->file_original_name .= "." . $arr[$i];
                    }
                }

                // if($extension == 'svg') {
                //     $sanitizer = new Sanitizer();
                //     // Load the dirty svg
                //     $dirtySVG = file_get_contents($file);

                //     // Pass it to the sanitizer and get it back clean
                //     $cleanSVG = $sanitizer->sanitize($dirtySVG);

                //     // Load the clean svg
                //     file_put_contents($file, $cleanSVG);
                // }

                $path = $file->store('uploads/all', 'public');
                $storagePath = 'storage/' . $path;

                // dd($path);
                $size = $file->getSize();

                // Return MIME type ala mimetype extension
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                // Get the MIME type of the file
                // $file_mime = finfo_file($finfo, base_path('public/storage/') . $path);

                // if ($type[$extension] == 'image' && get_setting('disable_image_optimization') != 1) {
                //     try {
                //         $img = Image::make($file->getRealPath())->encode();
                //         $height = $img->height();
                //         $width = $img->width();
                //         if ($width > $height && $width > 1500) {
                //             $img->resize(1500, null, function ($constraint) {
                //                 $constraint->aspectRatio();
                //             });
                //         } elseif ($height > 1500) {
                //             $img->resize(null, 800, function ($constraint) {
                //                 $constraint->aspectRatio();
                //             });
                //         }
                //         $img->save(base_path('public/') . $path);
                //         clearstatcache();
                //         $size = $img->filesize();
                //     } catch (\Exception $e) {
                //         //dd($e);
                //     }
                // }

                // if (env('FILESYSTEM_DRIVER') == 's3') {
                //     Storage::disk('s3')->put(
                //         $path,
                //         file_get_contents(base_path('public/') . $path),
                //         [
                //             'visibility' => 'public',
                //             'ContentType' =>  $extension == 'svg' ? 'image/svg+xml' : $file_mime
                //         ]
                //     );
                //     if ($arr[0] != 'updates') {
                //         unlink(base_path('public/') . $path);
                //     }
                // }

                $upload->extension = $extension;
                $upload->file_name = $storagePath;
                $upload->user_id = $user_id;
                $upload->user_type = $user_type;
                $upload->type = $type[$upload->extension];
                $upload->file_size = $size;
                // dd($upload);
                $upload->save();
                return $upload->id;
            }
        }
    }
}
