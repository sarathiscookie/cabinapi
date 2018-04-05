<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Cabin;
Use Carbon\Carbon;

class CabinLiteImageController extends Controller
{

    public $cabin_name;
    public $image_folder_name;
    public $cabin_id;

    /**
     * For storing cabinname , image folder
     *
     * @return cabinname and image folder name
     */

    public function __construct(Request $request) {
       $id = $request->route('id');

       $this->cabin_id = $id;
        $this->cabin_name = $this->getCabinName($id);
        $this->image_folder_name = 'public/' . $this->cabin_name;
    //$this->middleware(function ($request, $next) {

          //  $this->cabin_name = session()->has('cabin_name') ? session()->get('cabin_name') : [];
          //  $this->image_folder_name = 'public/' . $this->cabin_name;
           // return $next($request);
    // });
    }


    /**
     * Getting cabin name of the resource.
     * @param: $id
     * @return \Illuminate\Http\Response
     */
    public function getCabinName($id)
    {
        $get_cabin = Cabin::where('_id', $id)->first();
        $cname =   $get_cabin->name;
        return $cname;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //echo $id ;
        $images_arr = $this->getImages();
        $images   = $images_arr ;
         $cabin  = Cabin::where('_id', $id)->first();
     return view('backend.cabinLiteImage' , array('images' =>$images  , 'cabin'=>$cabin));
    }
    /**
     * Get all images fro older .
     *
     * @return \Illuminate\Http\Response -> Image array
     */
    public function getImages()
    {

        $folder = $this->image_folder_name;
        $images_arr = [];
        if (Storage::directories($folder)) {
            $images = Storage::allFiles($folder . '/thumb');
            foreach ($images as $path) {
                $images_arr[] = pathinfo($path);
            }
        }
        return $images_arr;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id   =  $this->cabin_id;

        return view('backend.cabinLiteImageCreate') ->with('id',   $id );
    }

    /*
     * getImages for fetch All images based on cabin name
     *
     *   * @param
         * @return images array
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cabin_name = $this->cabin_name;

        if ($request->hidden_base64Logo != '') {

            $code = $request->hidden_base64Logo;
            list($type, $code) = explode(';', $code);
            list(, $code) = explode(',', $code);
            $code = base64_decode($code);
            $current_time = strtotime(Carbon::now());
            $filename = $cabin_name . '_' . $current_time . '.jpg';

            $folder = $this->image_folder_name;
            $thumb = $this->image_folder_name . '/thumb';

            if (!(Storage::directories($folder))) {
                Storage::makeDirectory($folder);
                Storage::makeDirectory($thumb);

            }
            $file_size = $_FILES["logoUpload"]["size"];
            /***limiting fie size to 400kb****/
            if ($file_size > 409600) {

                //return view('cabinowner.imageCreate')->with('imagesStatus', __('image.wrongImageSize'));
            }
            Storage::disk('public')->put($cabin_name . '/' . $filename, $code);


            /**********create thumbnail starts *******************/

            $thumb_name = storage_path() . '/app/public/' . $cabin_name . '/thumb/' . $cabin_name . '_' . $current_time;
            $this->createThumb($request, $thumb_name);
            //create thumbnail ends *******************/


          return redirect('admin/cabinlite/image/'.$this->cabin_id)->with('imagesSuccessStatus', __('image.successMsgImageSave'));
        } else{

            $imagesStatus   = __('image.failedMsgImageSave') ;
            $id   =  $this->cabin_id;
            return view('backend.cabinLiteImageCreate')->with(compact('imagesStatus', 'id'));

        }


    }

    /*
     * function create thumb
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function createThumb($request, $dest)
    {

        $image = $request->file('logoUpload'); //tmp_name
        //$uploadedImageName = $request->logoUpload->getClientOriginalName(); // $_FILES["logoUpload"]["name"];
        $extension = $request->logoUpload->extension();
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $src = imagecreatefromjpeg($image);
        } else if ($extension == 'gif') {
            $src = imagecreatefromgif($image);
        } else if ($extension == 'png') {
            $src = imagecreatefrompng($image);
        }
        $old_image = $src;
        $image_size = getimagesize($image);
        $image_width = $image_size[0];
        $image_height = $image_size[1];
        $new_size = ($image_width + $image_height) / ($image_width * ($image_height / 45));
        $new_width = $image_width * $new_size;
        $new_height = $image_height * $new_size;
        $new_image = imagecreatetruecolor($new_width, $new_height);

        imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);

        $thumb_name = $dest . '.jpg';
        imagejpeg($new_image, $thumb_name, 100);

    }


    /*
     *
     */

    public function checkImg(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
            $thumb = $this->image_folder_name . '/thumb/' . $request->imagename;
            $img = $this->image_folder_name . '/' . $request->imagename;
            Storage::delete($thumb);
            Storage::delete($img);
            $folder = $this->image_folder_name;
            $images = Storage::allFiles($folder . '/thumb');
            if (count($images) <= 0) {
                Storage::deleteDirectory($folder);
            }
            $imgDiv = $this->imageDisplayBox();
            return response()->json(['images' => $imgDiv, 'imgDeleteStatus' => 'success', 'message' => __('image.imageDeleteSuccessResponse')], 201);
        }
    }

    /*
     * function imageDisplayBox for showing images after ajax calll of delete, set profile and set main imange
     *
     */
    public function imageDisplayBox()
    {
        $imgDiv = '';
        $folder = $this->image_folder_name;
        $images = Storage::allFiles($folder . '/thumb');
        if (count($images) <= 0) {
            $imgDiv = ' <p class="bg-info">' . __("image.noImage") . '</p>';
        } else {

            foreach ($images as $eachimage) {

                $image = pathinfo($eachimage);
                $imgDiv .= '<div class="col-md-4" id="' . $image['filename'] . '" >

                                <a  class = "thumbnail" ><img  src = "' . str_replace('public', '/storage', $image['dirname'] . '/' . $image['basename']) . '" alt = "Generic placeholder thumbnail" >';

                if (strpos($image['basename'], 'main_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.mainImg") . '</p >';
                } elseif (strpos($image['basename'], 'profile_') !== false) {
                    $imgDiv .= '<p class="bg-primary" >' . __("image.profileImg") . '</p >';
                } else {
                    $imgDiv .= '<button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_mainimg" >' . __("image.uploadSetmageButton") . '</button >&nbsp;<button value = "' . $image['basename'] . '" type = "button" class="btn btn-success set_profileimg" >' . __("image.uploadSetProfileButton") . '</button >';
                }

                $imgDiv .= ' <button class="img_button" type = "submit" value = "' . $image['basename'] . '" ><i class="fa fa-trash-o" aria - hidden = "true" ></i ></button ></a ></div >';

            }
        }
        return $imgDiv;
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

            $folder = $this->image_folder_name . '/';
            $thumbfolder = $folder . '/thumb/';
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


            $imgDiv = $this->imageDisplayBox();
            return response()->json(['images' => $imgDiv, 'imgsetMainStatus' => 'success', 'message' => __('image.imagesetMainSuccessResponse')], 201);
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
            $folder = $this->image_folder_name . '/';
            $thumbfolder = $folder . '/thumb/';
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

            $imgDiv = $this->imageDisplayBox();
            return response()->json(['images' => $imgDiv, 'imgsetMainStatus' => 'success', 'message' => __('image.imagesetprofileSuccessResponse')], 201);
        }
    }
}
