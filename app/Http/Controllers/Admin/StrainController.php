<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use Carbon\Carbon;
use App\Models\Strain;
use App\Models\StrainGallery;
use App\Models\Category;
use App\Models\Currency;
use Mtownsend\RemoveBg\RemoveBg;
use Maatwebsite\Excel\Facades\Excel;
use App\Classes\TransactionsImport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Validator;
use Image;
use Session;

class StrainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Strain::orderBy('id', 'desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('name', function (Strain $data) {
                $name = mb_strlen(strip_tags($data->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'utf-8') . '...' : strip_tags($data->name);
                return $name;
            })
            ->editColumn('thumb', function (Strain $data) {
                $thumb = $data->thumb();
                if($thumb['path'] == '') {
                    return '';
                }
                else {
                    return '<div style="text-align: center;"><img src="'. asset($thumb['path']).'" width="25px" height="25px;" /></div>';
                }
            })
            ->editColumn('effect', function (Strain $data) {
                $effect = mb_strlen(strip_tags($data->effect), 'utf-8') > 50 ? mb_substr(strip_tags($data->effect), 0, 50, 'utf-8') . '...' : strip_tags($data->effect);
                return $effect;
            })
            ->editColumn('description', function (Strain $data) {
                $description = mb_strlen(strip_tags($data->description), 'utf-8') > 50 ? mb_substr(strip_tags($data->description), 0, 50, 'utf-8') . '...' : strip_tags($data->description);
                return $description;
            })
            ->editColumn('parent', function (Strain $data) {
                $parent = mb_strlen(strip_tags($data->parent), 'utf-8') > 50 ? mb_substr(strip_tags($data->parent), 0, 50, 'utf-8') . '...' : strip_tags($data->parent);
                return $parent;
            })
            ->addColumn('action', function (Strain $data) {
                return '<div class="godropdown">
                    <button class="go-dropdown-toggle">Actions<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-strain-edit', $data->id) . '">
                            <i class="fas fa-edit"></i>Edit
                        </a>
                        <a href="' . route('admin-strain-gallery', $data->id) . '">
                            <i class="fas fa-eye"></i> View Gallery
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-strain-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['name', 'thumb', 'effect', 'description', 'parent', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.strains.index');
    }

    //*** GET Request
    public function createImport()
    {
        return view('admin.strains.createone');
    }

    public function store(Request $request)
    {
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
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Strain Added Successfully.';
        return response()->json($msg);


        //--- Redirect Section Ends    
    }

    public function edit(Request $request, $id)
    {
        $data = Strain::findOrFail($id);
        return view('admin.strains.editone', compact('data'));
    }

    public function update(Request $request, $id)
    {
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
        $data = Strain::findOrFail($id);
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

        //--- Redirect Section     
        $msg = 'Strain Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends            
    }

    public function destroy($id)
    {
        $data = Strain::findOrFail($id);
        $data->delete();

        File::deleteDirectory(public_path() . '/assets/images/strains/' . $id);
        StrainGallery::where('strain_id', $id)->delete();

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
                $total_record = StrainGallery::where('strain_id', $strain_id)->orderBy('id', 'desc')->get();

                if ($total_record->count() == 0) {
                    
                    $apiKey = "YAypVmKK55sfxF4SPZdMFLyx";
                    $removebg = new RemoveBg($apiKey);
                    
                    $main_img = Image::make($file->getRealPath())->resize(600, 600);
                    $responsive_img = Image::make($file->getRealPath())->resize(200, 200);

                    $file_name = $strain_id . '_logo.jpg';

                    $main_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $responsive_pathToFile = public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $file_name;

                    $main_img->save($main_pathToFile);
                    $responsive_img->save($responsive_pathToFile);

                    $message_flag = 'success';
                    $message = 'Uploaded Gallery Successfully';

                    try {
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
                    } catch (\Exception $e) {
                        $message_flag = 'error';
                        $message = $e->getMessage();
                        Session::put('error', $message);

                        $model = new StrainGallery;

                        $model->strain_id = $strain_id;
                        $model->path = 'assets/images/strains/' . $strain_id . '/top/' . $file_name;
                        $model->extension = $val;
                        $model->name = $file_name;
                        $model->index = 1;
                        $model->save();

                        return response()->json(array('msg' => 'fail'));
                    }

                    
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

                    $message_flag = 'success';
                    $message = 'Uploaded Gallery Successfully';

                    try {
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
                    } catch (\Exception $e) {
                        $message_flag = 'error';
                        $message = $e->getMessage();
                        Session::put('error', $message);

                        $model = new StrainGallery;

                        $model->strain_id = $strain_id;
                        $model->path = 'assets/images/strains/' . $strain_id . '/top/' . $file_name;
                        $model->extension = $val;
                        $model->name = $file_name;
                        $model->index = 1;

                        $model->save();

                        return response()->json(array('msg' => 'fail'));
                    }

                    $model = new StrainGallery;

                    $model->strain_id = $strain_id;
                    $model->path = 'assets/images/strains/' . $strain_id . '/top/' . $file_name;
                    $model->extension = $val;
                    $model->name = $file_name;
                    $model->index = 0;

                    $model->save();
                }
            }
        }

        // return response()->json($data);
        $msg = 'Uploaded Gallery Successfully.';
        Session::put('success', $msg);
        return response()->json(array('msg' => 'success'));
    }

    public function gallery_remove($gallery_id)
    {
        $strain_id = StrainGallery::find($gallery_id)->strain_id;
        $name = StrainGallery::find($gallery_id)->name;
        $path = StrainGallery::find($gallery_id)->path;
        
        if (file_exists(public_path() . '/' . $path) && file_exists(public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $name)) {
            unlink(public_path() . '/' .$path);
            unlink(public_path() . '/assets/images/strains/' . $strain_id . '/responsive/' . $name);
        }

        StrainGallery::find($gallery_id)->delete();
        return redirect()->back();
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

    public function excel_upload(Request $request) {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $file = $request->excel_file;

        if ($file) {

            try {
                Excel::import(new TransactionsImport, $request->excel_file);

                $msg = 'New File Uploaded Successfully.';
                Session::put('success', $msg);

                return response()->json([
                    "status" => "success",
                    "url" => route('admin-strain-index')
                ], 200);

            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "Some Error occur during saving"
                ], 400);
            }

        } 
        else {
            return response()->json([
                "status" => "error",
                "message" => "file not found"
            ], 400);
        }
    }


    public function searchStrain(Request $request) {
        $keyword = $request['keyword'];
        if (mb_strlen($keyword, 'utf-8') > 1) {
            $strains = Strain::where('name', 'like', '%' . $keyword . '%')->take(10)->get();
            $route = 'admin-strain-gelleries';
            return view('load.strains', compact('strains', 'route'));
        }
        return "";
    }

    
    public function getStrainGalleries(Request $request, $id) {
        $data = Strain::findOrFail($id);
        $galleries = $data->galleries;
        $data = [];
        foreach($galleries as $gallery) {
            $item = [];
            $item['asset'] = $gallery->path;
            $item['path'] = asset($gallery->path);
            array_push($data, $item);
        }
        return response()->json($data);
    }

}
