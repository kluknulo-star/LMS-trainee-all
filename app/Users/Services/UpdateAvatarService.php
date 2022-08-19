<?php

namespace App\Users\Services;

use Intervention\Image\Facades\Image;

class UpdateAvatarService
{
    public function updateAvatar($avatar): bool
    {
        auth()->user()->clearAvatars(auth()->id());
        $filename = time() . '.' . $avatar->getClientOriginalExtension();
        Image::make($avatar)->resize(300, 300)
            ->save( public_path(auth()->user()->getAvatarsPath(auth()->id()) . $filename ) );

        auth()->user()->avatar_filename = $filename;
        return auth()->user()->save();
    }
}
