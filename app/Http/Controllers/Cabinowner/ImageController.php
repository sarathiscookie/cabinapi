<?php

namespace App\Http\Controllers\Cabinowner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
Use Carbon\Carbon;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabin_name = session('cabin_name');
        $folder = 'public/' . $cabin_name;
        $images_arr = '';
        if (Storage::directories($folder)) {

            $images = Storage::allFiles($folder . '/thumb');
            $images_arr = [];


            foreach ($images as $path) {

                $images_arr[] = pathinfo($path);
            }


        }
        return view('cabinowner.image')->with('images', $images_arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cabinowner.imageCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cabin_name = session('cabin_name');

        if ($request->hidden_base64Logo != '') {

            $code = $request->hidden_base64Logo;
            list($type, $code) = explode(';', $code);
            list(, $code) = explode(',', $code);
            $code = base64_decode($code);
            $current_time = strtotime(Carbon::now());
            $filename = $cabin_name . '_' . $current_time . '.jpg';

            $folder = 'public/' . $cabin_name;
            $thumb = 'public/' . $cabin_name . '/thumb';

            if (!(Storage::directories($folder))) {
                Storage::makeDirectory($folder);
                Storage::makeDirectory($thumb);

            }
            $file_size = $_FILES["logoUpload"]["size"];
            /***limiting fie size to 400kb****/
            if($file_size > 409600)
            {

                return view('cabinowner.imageCreate')->with('imagesStatus',__('image.wrongImageSize') );
            }
            Storage::disk('public')->put($cabin_name . '/' . $filename, $code);


            /**********create thumbnail starts *******************/

            $fname = $_FILES["logoUpload"]["name"];
             $uploadedfile = $_FILES['logoUpload']['tmp_name'];
            if (preg_match('/[.](jpg)$/', $fname)) {
                $src = imagecreatefromjpeg($uploadedfile);
            } else if (preg_match('/[.](gif)$/', $fname)) {
                $src = imagecreatefromgif($uploadedfile);
            } else if (preg_match('/[.](png)$/', $fname)) {
                $src = imagecreatefrompng($uploadedfile);
            }


            list($width, $height) = getimagesize($uploadedfile);
            $newwidth1 = 250;
            $newheight1 = 150;
            $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
            imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
            $filename1 = storage_path() . '/app/public/' . $cabin_name . '/thumb/' . $cabin_name . '_' . $current_time . '.jpg';
            imagejpeg($tmp1, $filename1, 100);
            //create thumbnail ends *******************/

            return redirect(url('cabinowner/image'))->with('successMsgImageSave', __('image.successMsgImageSave'));

        } else
            return redirect(url('cabinowner/image/create'))->with('successMsgImageSave', __('image.failedMsgImageSave'));

    }
    public function checkImg(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request $request)
    {
        if ($request->imagename != '') {
            $cabin_name = session('cabin_name');
            $thumb = 'public/' . $cabin_name . '/thumb/' . $request->imagename;
            $img = 'public/' . $cabin_name . '/' . $request->imagename;
            Storage::delete($thumb);
            Storage::delete($img);
            $folder = 'public/' . $cabin_name;

            $images = Storage::allFiles($folder . '/thumb');
            $images_arr = [];


            $imgDiv = '';

            foreach ($images as $eachimage) {

                $image = pathinfo($eachimage);
                $imgDiv .= '<div class="col-md-4" id="' . $image['filename'] . '" >
                                <a  class = "thumbnail" ><img  src = "' . str_replace('public', '/storage', $image['dirname'] . '/' . $image['basename']) . '" alt = "Generic placeholder thumbnail" >';

                if (strpos($image['basename'], 'main_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.mainImg") . '</p >';
                } elseif (strpos($image['basename'], 'profile_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.profileImg") . '</p >';
                }else {
                    $imgDiv .= '<button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_mainimg" >' . __("image.uploadSetmageButton") . '</button ><button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_profileimg" >' . __("image.uploadSetProfileButton") . '</button >';
                }

                $imgDiv .= ' <button class="img_button" type = "submit" value = "' . $image['basename'] . '" ><i class="fa fa-trash-o" aria - hidden = "true" ></i ></button ></a ></div >';

            }

            return response()->json(['images' => $imgDiv, 'imgDeleteStatus' => 'success','message' => __('image.imageDeleteSuccessResponse')], 201);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setMainImg(Request $request)
    {
        if ($request->imagename != '') {
            $cabin_name = session('cabin_name');
            $thumb = 'public/' . $cabin_name . '/thumb/' . $request->imagename;
            $img = 'public/' . $cabin_name . '/' . $request->imagename;

            $folder = 'public/' . $cabin_name . '/';
            $thumbfolder = 'public/' . $cabin_name . '/thumb/';

            $images = Storage::allFiles($folder . '/thumb');


            foreach ($images as $image) {

                $img_det = pathinfo($image);
                /****renaming old main image ***/
                if (strpos($img_det['basename'], 'main_') !== false) {

                    $new_image = str_replace("main_", "", $img_det['basename']);

                    Storage::move($thumbfolder . $img_det['basename'], $thumbfolder . $new_image);
                    Storage::move($folder . $img_det['basename'], $folder . $new_image);
                }
                if ($img_det['basename'] == $request->imagename) {
                    /***setting main image***/
                    $main_image = "main_" . $request->imagename;
                    Storage::move($thumbfolder . $img_det['basename'], $thumbfolder . $main_image);
                    Storage::move($folder . $img_det['basename'], $folder . $main_image);
                }
            }


            $thumbimages = Storage::allFiles($folder . '/thumb');

            $imgDiv = '';

            foreach ($thumbimages as $eachimage) {
                $image = pathinfo($eachimage);
                $imgDiv .= '<div class="col-md-4" id="' . $image['filename'] . '" ><a  class = "thumbnail" >
                                <img  src = "' . str_replace('public', '/storage', $image['dirname'] . '/' . $image['basename']) . '" alt = "Generic placeholder thumbnail" >';

                if (strpos($image['basename'], 'main_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.mainImg") . '</p >';
                } elseif (strpos($image['basename'], 'profile_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.profileImg") . '</p >';
                }else {
                    $imgDiv .= '<button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_mainimg" >' . __("image.uploadSetmageButton") . '</button ><button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_profileimg" >' . __("image.uploadSetProfileButton") . '</button >';
                }

                $imgDiv .= ' <button class="img_button" type = "submit" value = "' . $image['basename'] . '" ><i class="fa fa-trash-o" aria - hidden = "true" ></i ></button ></a ></div >';

            }
            return response()->json(['images' => $imgDiv, 'imgsetMainStatus' => 'success','message' => __('image.imagesetMainSuccessResponse')], 201);
        }
    }
    /**
     * Set profile Image.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setProfileImg(Request $request)
    {
        if ($request->imagename != '') {
            $cabin_name = session('cabin_name');
            $thumb = 'public/' . $cabin_name . '/thumb/' . $request->imagename;
            $img = 'public/' . $cabin_name . '/' . $request->imagename;

            $folder = 'public/' . $cabin_name . '/';
            $thumbfolder = 'public/' . $cabin_name . '/thumb/';

            $images = Storage::allFiles($folder . '/thumb');


            foreach ($images as $image) {

                $img_det = pathinfo($image);
                /****renaming old main image ***/
                if (strpos($img_det['basename'], 'profile_') !== false) {

                    $new_image = str_replace("profile_", "", $img_det['basename']);

                    Storage::move($thumbfolder . $img_det['basename'], $thumbfolder . $new_image);
                    Storage::move($folder . $img_det['basename'], $folder . $new_image);
                }
                if ($img_det['basename'] == $request->imagename) {
                    /***setting main image***/
                    $main_image = "profile_" . $request->imagename;
                    Storage::move($thumbfolder . $img_det['basename'], $thumbfolder . $main_image);
                    Storage::move($folder . $img_det['basename'], $folder . $main_image);
                }
            }


            $thumbimages = Storage::allFiles($folder . '/thumb');

            $imgDiv = '';

            foreach ($thumbimages as $eachimage) {
                $image = pathinfo($eachimage);
                $imgDiv .= '<div class="col-md-4" id="' . $image['filename'] . '" ><a  class = "thumbnail" >
                                <img  src = "' . str_replace('public', '/storage', $image['dirname'] . '/' . $image['basename']) . '" alt = "Generic placeholder thumbnail" >';

                if (strpos($image['basename'], 'main_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.mainImg") . '</p >';
                } elseif (strpos($image['basename'], 'profile_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.profileImg") . '</p >';
                }else {
                    $imgDiv .= '<button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_mainimg" >' . __("image.uploadSetmageButton") . '</button ><button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_profileimg" >' . __("image.uploadSetProfileButton") . '</button >';
                }

                $imgDiv .= ' <button class="img_button" type = "submit" value = "' . $image['basename'] . '" ><i class="fa fa-trash-o" aria - hidden = "true" ></i ></button ></a ></div >';

            }
            return response()->json(['images' => $imgDiv, 'imgsetMainStatus' => 'success','message' => __('image.imagesetMainSuccessResponse')], 201);
        }
    }
}
