<?php
namespace Modules\Core\Packages\SewidanFields;

use Illuminate\Support\Facades\File;
use IlluminateAgnostic\Arr\Support\Carbon;

class CkeditorUploader
{
    static function upload($request){
    
        $tenant = app('currentTenant');
        $file = $request->image;
        $folder_name = "ck-editor-images/".($tenant ? "{$tenant->subdomain}/" : ''). Carbon::now()->year . '/' . Carbon::now()->month . '/' . Carbon::now()->day;
        $destinationPath = public_path().'/uploads/'.$folder_name.'/';
        $extension = $file->getClientOriginalExtension(); // getting image extension

        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        $image = '-'.time().''.rand(11111, 99999).'.'.$extension;
        $file->move($destinationPath, $image); // uploading file to given
        $path = '/uploads/'.$folder_name.'/'.$image;

        return response()->json(['url' => $path]);
    }
}


