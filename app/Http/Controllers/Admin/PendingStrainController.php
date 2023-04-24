<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Strain;
use App\Models\Product;
use App\Models\StrainGallery;
use App\Models\PendingStrain;
use App\Models\StrainTemp;
use App\Models\StrainGalleryTemp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Validator;
use Image;
use Session;

class PendingStrainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $vendor_datas = PendingStrain::all();

        $user_datas = StrainTemp::all();

        $pending_strains = Array();

        foreach($vendor_datas as $item) {
            $item_array = [];
            $item_array['name'] = mb_strlen(strip_tags($item->get_product->name), 'utf-8') > 50 ? mb_substr(strip_tags($item->get_product->name), 0, 50, 'utf-8') . '...' : strip_tags($item->get_product->name);
            $item_array['effect'] = mb_strlen(strip_tags($item->get_product->effects), 'utf-8') > 50 ? mb_substr(strip_tags($item->get_product->effects), 0, 50, 'utf-8') . '...' : strip_tags($item->get_product->effects);
            $item_array['description'] = mb_strlen(strip_tags($item->get_product->details), 'utf-8') > 50 ? mb_substr(strip_tags($item->get_product->details), 0, 50, 'utf-8') . '...' : strip_tags($item->get_product->details);
            $item_array['parent'] = mb_strlen(strip_tags($item->get_product->parent), 'utf-8') > 50 ? mb_substr(strip_tags($item->get_product->parent), 0, 50, 'utf-8') . '...' : strip_tags($item->get_product->parent);
            $item_array['id'] = $item->product_id;
            $item_array['author'] = 'Vendor:<br><a href="' . route('admin-vendor-show', $item->get_product->user->id) . '" >'.$item->get_product->user->name.'</a>';
            $item_array['type'] = 'vendor';
            array_push($pending_strains, $item_array);
        }

        foreach($user_datas as $item) {
            $item_array = [];
            $item_array['name'] = mb_strlen(strip_tags($item->name), 'utf-8') > 50 ? mb_substr(strip_tags($item->name), 0, 50, 'utf-8') . '...' : strip_tags($item->name);
            $item_array['effect'] = mb_strlen(strip_tags($item->effect), 'utf-8') > 50 ? mb_substr(strip_tags($item->effect), 0, 50, 'utf-8') . '...' : strip_tags($item->effect);
            $item_array['description'] = mb_strlen(strip_tags($item->description), 'utf-8') > 50 ? mb_substr(strip_tags($item->description), 0, 50, 'utf-8') . '...' : strip_tags($item->description);
            $item_array['parent'] = mb_strlen(strip_tags($item->parent), 'utf-8') > 50 ? mb_substr(strip_tags($item->parent), 0, 50, 'utf-8') . '...' : strip_tags($item->parent);
            $item_array['id'] = $item->id;
            $item_array['author'] = 'Customer:<br><a href="' . route('admin-user-show', $item->user->id) . '" >'.$item->user->name.'</a>';
            $item_array['type'] = 'user';
            array_push($pending_strains, $item_array);
        }




        //--- Integrating This Collection Into Datatables
        return Datatables::of($pending_strains)
            
            ->addColumn('action', function ($data) {
                $action =  '<div class="godropdown">
                    <button class="go-dropdown-toggle">Actions<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="'. route('admin-pendingstrain-accept', $data['id']) .'">
                            <i class="fas fa-check"></i>Accept
                        </a>
                        <a href="javascript:;" data-href="'. route('admin-pendingstrain-delete', $data['id']) .'" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                </div>';

                if($data['type'] == 'user') {
                    $action =  '<div class="godropdown">
                        <button class="go-dropdown-toggle">Actions<i class="fas fa-chevron-down"></i></button>
                        <div class="action-list">
                            <a href="'. route('admin-pendingstrain-cusaccept', $data['id']) .'">
                                <i class="fas fa-check"></i>Accept
                            </a>
                            <a href="javascript:;" data-href="'. route('admin-pendingstrain-cusdelete', $data['id']) .'" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </div>
                    </div>';
                }

                return $action;
            })
            ->rawColumns(['action', 'author'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.pendingstrains.index');
    }

    //*** GET Request
    public function accept(Request $request, $id)
    {
        if(count(PendingStrain::where('product_id', $id)->get()) == 0){
            return redirect()->route('admin-pendingstrain-index');
        }
        $data = Product::findOrFail($id);
        return view('admin.pendingstrains.editone', compact('data'));
    }

    public function store(Request $request)
    {
        if(count(PendingStrain::where('product_id', $request->product_id)->get()) == 0){
            return response()->json(array('errors' => ["You have already accepted this strain. <a href='". route('admin-strain-index') ."'>Back to all stains</a>"]));
        }
        //--- Validation Section
        $rules = array(
            'name' => 'required',
            'effect' => 'required',
            'description' => 'required',
            'parent' => 'required'
        );
        $customs = array(
            'name.required' => 'name field is required.',
            'effect.required' => 'effect field is required.',
            'description.required' => 'description field is required',
            'parent.required' => 'parent field is required'
        );

        $validator = Validator::make(  $request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Strain();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        // Moving Product Gallery to Strain Gallery

        $prod = Product::find($request->product_id);

        if($prod->photo) {
            $source = public_path().'/assets/images/products/'.$prod->photo;
            $destination_dir = public_path().'/assets/images/strains/'.$data->id.'/top';
            $destination = $destination_dir.'/'.$data->id.'_logo.jpg';
            if(!file_exists($destination_dir))
                mkdir($destination_dir, 0777, true);
            copy($source, $destination);

            $strain_gallery = new StrainGallery();
            $strain_gallery->strain_id = $data->id;
            $strain_gallery->path = '/assets/images/strains/'.$data->id.'/top'.'/'.$data->id.'_logo.jpg';
            $strain_gallery->extension = 'png';
            $strain_gallery->name = $data->id.'_logo.jpg';
            $strain_gallery->index = 1;
            $strain_gallery->save();


            $i = 0;
            foreach($prod->galleries as $gallery) {
                $source = public_path().'/assets/images/products/'.$prod->photo;
                $destination_dir = public_path().'/assets/images/strains/'.$data->id.'/top';
                $destination = $destination_dir.'/'.$data->id.'_'. $i .'.png';
                if(!file_exists($destination_dir))
                    mkdir($destination_dir, 0777, true);
                copy($source, $destination);

                $strain_gallery = new StrainGallery();
                $strain_gallery->strain_id = $data->id;
                $strain_gallery->path = '/assets/images/strains/'.$data->id.'/top'.'/'.$data->id.'_'. $i .'.png';
                $strain_gallery->extension = 'png';
                $strain_gallery->name = $data->id.'_'. $i .'.png';
                $strain_gallery->index = 0;
                $strain_gallery->save();

                $i++;
            }
        }

        

        // --- End ---

        PendingStrain::where('product_id', $request->product_id)->delete();

        //--- Redirect Section        
        $msg = 'New Strain Added Successfully.';
        return response()->json($msg);


        //--- Redirect Section Ends    
    }


    public function destroy($id)
    {
        $data = PendingStrain::where('product_id', $id)->delete();
        $msg = 'Strain Deleted Successfully.';
        return response()->json($msg);
    }

    public function cus_accept(Request $request, $id)
    {
        $data = StrainTemp::find($id);

        if(!$data) {
            return redirect()->route('admin-pendingstrain-index');
        }

        $strain = Strain::find($data->strain_id);
        return view('admin.pendingstrains.editcusstrain', compact('data', 'strain'));
    }

    public function cus_store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'effect' => 'required',
            'description' => 'required',
            'parent' => 'required'
        );
        $customs = array(
            'name.required' => 'name field is required.',
            'effect.required' => 'effect field is required.',
            'description.required' => 'description field is required',
            'parent.required' => 'parent field is required'
        );

        $validator = Validator::make(  $request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $strainTemp = StrainTemp::findOrFail($request->pending_strain_id);

        
        if($request->strain_id) {
            $data = Strain::findOrFail($request->strain_id);
            $input = $request->all();
            $input['effect'] = strtolower($input['effect']);
            $segments = explode(', ', $input['effect']);
            $input['effect'] = "";

            foreach($segments as $seg) {
                $seg = str_replace(" ", "-", $seg);
                $input['effect'] = $input['effect'].$seg.", ";
            }

            if($input['effect'] != "") {
                $input['effect'] = substr($input['effect'], 0, -2);
            }


            

            
            $data->update($input);
            //--- Logic Section Ends
            $main_path = public_path() . '/assets/images/strains/' . $data->id;
            if (!File::isDirectory($main_path)) {
                File::makeDirectory($main_path, 0777, true, true);
            }

            foreach($strainTemp->galleries as $gallery) {
                if (file_exists(public_path() .'/'. $gallery->path)) {
                    $source = public_path().'/'. $gallery->path;  
                    $fileName = pathinfo($source);
                    $fileName = $fileName['basename'];
                    // Store the path of destination file 
                    $destination = public_path().'/assets/images/strains/'.$data->id.'/'.$fileName;
                    copy($source, $destination);
                    $model = new StrainGallery;
                    $model->strain_id = $data->id;
                    $model->path = '/assets/images/strains/'.$data->id.'/'.$fileName;
                    $model->extension = 'png';
                    $model->name = $fileName;
                    $model->index = 1;
                    $model->save();
                    unlink(public_path() . '/' . $gallery->path);
                }
            }

            $msg = 'The Strain Updated Successfully.';
        } else {

            $data = new Strain();
            $input = $request->all();

            $input['effect'] = strtolower($input['effect']);
            $segments = explode(', ', $input['effect']);
            $input['effect'] = "";

            foreach($segments as $seg) {
                $seg = str_replace(" ", "-", $seg);
                $input['effect'] = $input['effect'].$seg.", ";
            }

            if($input['effect'] != "") {
                $input['effect'] = substr($input['effect'], 0, -2);
            }

            $data->fill($input)->save();
            
            $main_path = public_path() . '/assets/images/strains/' . $data->id;
            if (!File::isDirectory($main_path)) {
                File::makeDirectory($main_path, 0777, true, true);
            }

            foreach($strainTemp->galleries as $gallery) {
                if (file_exists(public_path() .'/'. $gallery->path)) {
                    $source = public_path().'/'. $gallery->path;  
                    $fileName = pathinfo($source);
                    $fileName = $fileName['basename'];
                    // Store the path of destination file 
                    $destination = public_path().'/assets/images/strains/'.$data->id.'/'.$fileName;
                    copy($source, $destination);
                    $model = new StrainGallery;
                    $model->strain_id = $data->id;
                    $model->path = '/assets/images/strains/'.$data->id.'/'.$fileName;
                    $model->extension = 'png';
                    $model->name = $fileName;
                    $model->index = 1;
                    $model->save();
                    unlink(public_path() . '/' . $gallery->path);
                }
            }

            $msg = 'New Strain Updated Successfully.';
        }

        
        $strainTemp->delete();
        
        return response()->json($msg);
    }


    public function cus_destroy($id)
    {
        $data = StrainTemp::findOrFail($id);
        $data->delete();
        $msg = 'Strain Deleted Successfully.';
        return response()->json($msg);
    }

    public function gallery(Request $request, $id)
    {
        $data = StrainGallery::where('strain_id', $id)->get();
        $strain_id = $id;
        return view('admin.strains.gallery', compact('data', 'strain_id'));
    }

    public function gallery_upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $data = array();
        $strain_id = $request->strain_id;

        if ($request->hasFile('profile_picture')) {

            $file = $request->file('profile_picture');

            $main_path = public_path() . '/assets/images/strains/' . $strain_id . '/top';
            $responsive_path = public_path() . '/assets/images/strains/' . $strain_id . '/responsive';

            if (!File::isDirectory($main_path) || !File::isDirectory($responsive_path)) {
                File::makeDirectory($main_path, 0777, true, true);
                File::makeDirectory($responsive_path, 0777, true, true);
            }

            $val = $file->guessExtension();

            if ($val == 'jpeg' || $val == 'jpg' || $val == 'png' || $val == 'svg') {
                $total_record = StrainGallery::orderBy('id', 'desc')->get();

                if ($total_record->count() == 0) {
                    $last_id = 1;

                    $apiKey = "YAypVmKK55sfxF4SPZdMFLyx";
                    $removebg = new RemoveBg($apiKey);
                    
                    $main_img = Image::make($file->getRealPath())->resize(600, 600);
                    $responsive_img = Image::make($file->getRealPath())->resize(200, 200);

                    $file_name = $strain_id . '_logo.jpg';

                    $main_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $responsive_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $file_name;

                    $main_img->save($main_pathToFile);
                    $responsive_img->save($responsive_pathToFile);

                    $removebg->file($main_pathToFile)
                    ->headers([
                        'X-Width' => 600,
                        'X-Height' => 600,
                    ])
                    ->body([
                        'size' => '4k', // regular, medium, hd, 4k, auto
                        'channels' => 'rgba', // rgba, alpha
                    ])
                    ->save($main_pathToFile);

                    $removebg->file($responsive_pathToFile)
                    ->headers([
                        'X-Width' => 200,
                        'X-Height' => 200,
                    ])
                    ->body([
                        'size' => '4k', // regular, medium, hd, 4k, auto
                        'channels' => 'rgba', // rgba, alpha
                    ])
                    ->save($responsive_pathToFile);
                    
                    $model = new StrainGallery;

                    $model->strain_id = $strain_id;
                    $model->path = 'assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $model->extension = $val;
                    $model->name = $file_name;
                    $model->index = 1;

                    $model->save();
                } else {
                    $last_record = StrainGallery::orderBy('id', 'desc')->first();
                    $last_id = $last_record->id;
                    $last_id++;

                    $apiKey = "YAypVmKK55sfxF4SPZdMFLyx";
                    $removebg = new RemoveBg($apiKey);

                    $main_img = Image::make($file->getRealPath())->resize(600, 600);
                    $responsive_img = Image::make($file->getRealPath())->resize(200, 200);

                    $file_name = $strain_id . '_' . $last_id . '.jpg';

                    $main_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $responsive_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $file_name;

                    $main_img->save($main_pathToFile);
                    $responsive_img->save($responsive_pathToFile);

                    $removebg->file($main_pathToFile)
                    ->headers([
                        'X-Width' => 600,
                        'X-Height' => 600,
                    ])
                    ->body([
                        'size' => '4k', // regular, medium, hd, 4k, auto
                        'channels' => 'rgba', // rgba, alpha
                    ])
                    ->save($main_pathToFile);

                    $removebg->file($responsive_pathToFile)
                    ->headers([
                        'X-Width' => 200,
                        'X-Height' => 200,
                    ])
                    ->body([
                        'size' => '4k', // regular, medium, hd, 4k, auto
                        'channels' => 'rgba', // rgba, alpha
                    ])
                    ->save($responsive_pathToFile);

                    $model = new StrainGallery;

                    $model->strain_id = $strain_id;
                    $model->path = 'assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $model->extension = $val;
                    $model->name = $file_name;
                    $model->index = 0;

                    $model->save();
                }

                $insert_data = array(
                    'id' => $model->id,
                    'strain_id' => $strain_id,
                    'path' => 'assets/images/strains/' . $strain_id . '/top/' . $file_name,
                    'extension' => $val,
                    'name' => $file_name,
                    'index' => 0
                );

                array_push($data, $insert_data);
            }
        }

        return response()->json($data);
    }

    public function gallery_remove(Request $request)
    {
        $gallery_id = $request->gallery_id;

        $gallery = StrainGalleryTemp::findOrFail($gallery_id);


        if (file_exists(public_path() .'/'. $gallery->path)) {
            unlink(public_path() . '/' . $gallery->path);
        }

        $gallery->delete();
        echo 'success';
    }

    public function gallery_logo(Request $request)
    {
        $strain_id = $request->strain_id;
        $gallery_id = $request->gallery_id;

        $origin_name = $strain_id . '_' . $gallery_id . '.jpg';
        $origin_top_path = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $origin_name;
        $origin_responsive_path = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $origin_name;

        $new_name = $strain_id . '_logo.jpg';
        $new_top_path = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $new_name;
        $new_responsive_path = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $new_name;

        $exist_record = StrainGallery::where('index', 1)
            ->where('strain_id', $strain_id)
            ->first();

        $exist_flag = 0;

        if (file_exists($new_top_path) && file_exists($new_responsive_path) && !empty($exist_record)) {
            $exist_id = $exist_record->id;

            $exist_name = $strain_id . '_' . $exist_id . '.jpg';
            $exist_top_path = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $exist_name;
            $exist_responsive_path = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $exist_name;

            if (rename($new_top_path, $exist_top_path) && rename($new_responsive_path, $exist_responsive_path)) {
                $exist_flag = 0;
            } else {
                $exist_flag = 1;
            }
        }

        if ($exist_flag == 1) {
            echo 'failure';
        } else {
            $exist_update_data = array(
                'path' => 'assets/images/strains/' . $strain_id . '/top/' . $exist_name,
                'name' => $exist_name,
                'index' => 0
            );

            StrainGallery::find($exist_id)->update($exist_update_data);

            $flag = 0;

            if (file_exists($origin_top_path) && file_exists($origin_responsive_path)) {
                if (rename($origin_top_path, $new_top_path) && rename($origin_responsive_path, $new_responsive_path)) {
                    $flag = 0;
                } else {
                    $flag = 1;
                }
            }

            if ($flag == 1) {
                echo 'failure';
            } else {
                $update_data = array(
                    'path' => 'assets/images/strains/' . $strain_id . '/top/' . $new_name,
                    'name' => $new_name,
                    'index' => 1
                );

                StrainGallery::find($gallery_id)->update($update_data);

                $return_data = array(
                    'message' => 'success',
                    'exist_id' => $exist_id,
                    'gallery_id' => $gallery_id,
                    'exist_path' => 'assets/images/strains/' . $strain_id . '/top/' . $exist_name,
                    'new_path' => 'assets/images/strains/' . $strain_id . '/top/' . $new_name,
                );

                echo json_encode($return_data);
            }
        }
    }

}
