<?php

namespace App\Http\Controllers;



use App\BlockList;
use App\User;
use Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use AWS;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function showUserPost(Request $request) {
        $user      = User::find($request->user_id);
        $user_post = $user->posts()
            ->where('rank','>=', $request->rank)
            ->where('deleted', '0')
            ->latest()
            ->get()
            ->toArray();
        return json_encode($user_post);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getUser(Request $request) {
        $user  = User::find($request->user_id)->first()->toArray();
        $block = BlockList::where('block_id', $request->id)
                 ->where('user_id', $request->user_id)
                 ->first();
        if($block != null) $user['blocked'] = 1; else $user['blocked'] = 0;
        return json_encode($user);
    }

    /**
     * @return string
     */
    public function getAllUser() {
        $user = User::all()->toArray();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeNickname(Request $request) {
        if(User::where('nickname', $request->nickname)->first() != null) return "Nickname already used";
        $user = User::find($request->id);
        $user -> nickname = $request->nickname;
        $user -> save();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeAvatar(Request $request) {
        $photo = $request->file('photo');
        $fn = $photo;
        $size = getimagesize($fn);
        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $width = 300;
            $height = 300/$ratio;
        }
        else {
            $width = 300*$ratio;
            $height = 300;
        }
        Image::make($fn)
            ->resize($width, $height)
            ->save(public_path().'/test.jpg', '60');
        $fn = $photo;
        $size = getimagesize($fn);
        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $width = 1200;
            $height = 1200/$ratio;
        }
        else {
            $width = 1200*$ratio;
            $height = 1200;
        }
        Image::make($fn)
            ->resize($width, $height)
            ->save(public_path().'/avatar.jpg', '75');

        DB::beginTransaction();
        $name1 = md5(microtime()).str_random(10).'.jpeg';
        $name2 = md5(microtime()).str_random(10).'_mini.jpeg';
        $path1 = Storage::putFileAs('users/'.$request->id.'/avatars', new File(public_path().'/avatar.jpg'), $name1 ,'public');
        $path2 = Storage::putFileAs('users/'.$request->id.'/avatars', new File(public_path().'/test.jpg'), $name2 ,'public');
        Storage::setVisibility($path2, 'public');
        Storage::setVisibility($path1, 'public');
        $url_photo1 = Storage::url($path1);
        $url_photo2 = Storage::url($path2);
        $user = User::find($request->id);
        $user -> profile_photo      = $url_photo1;
        $user -> profile_photo_mini = $url_photo2;
        $user -> save();
        DB::commit();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeName(Request $request) {
        $user = User::find($request->id);
        $user -> name = $request->name;
        $use r-> save();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeStatus(Request $request) {
        $user = User::find($request->id);
        $user -> status = $request->status;
        $user -> save();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeSite(Request $request) {
        $user = User::find($request->id);
        $user -> age = $request->site;
        $user -> save();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeBackgroundPhoto(Request $request) {
        DB::beginTransaction();
        Image::make($request->file('photo'))
            ->save(public_path().'/background.jpg', '80');
        $name = md5(microtime()).str_random(10).'.jpeg';
        $path = Storage::putFileAs('users/'.$request->id.'/background', new File(public_path().'/background.jpg'), $name ,'public');
        Storage::setVisibility($path, 'public');
        $url_photo = Storage::url($path);
        $user = User::find($request->id);
        $user -> background_photo = $url_photo;
        $user ->save();
        DB::commit();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeUser(Request $request) {
        if(User::where('nickname', $request->nickname)->first() != null) return "Nickname already used";
        $user = User::find($request->id);
        $user -> nickname   = $request->nickname;
        $user -> name       = $request->name;
        $user -> status     = $request->status;
        $user -> age        = $request->age;
        $user -> save();
        return json_encode($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changePassword(Request $request) {
        if (Auth::attempt(['id' => $request->id, 'password' => $request->password])) {
            $user = User::find($request->id);
            $user -> password = bcrypt($request->newpassword);
            $user -> save();
            return "Change";
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function changeEmail(Request $request) {
        $user = User::find($request->id);
        $user -> email = $request->email;
        $user -> save();
        return "Change";
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function setSNSToken(Request $request) {
        $user = User::find($request->id);
        $user2 = User::where('SnsToken', $request->SnsToken)
            ->where('id', '!=', $request->id)
            ->first();
        if($user2 != null) {
            if ($request->id != $user2->id) {
                $user2 = User::find($user2->id);
                $user2 -> SnsToken = Null;
                $user2 -> save();
            }
        }
        if($user->SnsToken != $request->SnsToken) {
            $user -> SnsToken = $request->SnsToken;
            $user -> save();
            return 'True';
        }
        return 'True';
    }
}
