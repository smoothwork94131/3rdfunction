<?php

namespace App\Http\Controllers\Admin;

use App\Models\Generalsetting;
use Artisan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Currency;
use App\Models\ColorSetting;
use App\Http\Controllers\Controller;
use Validator;

class ProductViewSettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    private function setEnv($key, $value, $prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    // Genereal Settings All post requests will be done in this method
    public function generalupdate(Request $request)
    {
        //--- Validation Section
        $validator = Validator::make(  $request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        else {
            $input = $request->all();
            $data = Generalsetting::findOrFail(1);
            if ($file = $request->file('logo')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->logo);
                $input['logo'] = $name;
            }
            if ($file = $request->file('favicon')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->favicon);
                $input['favicon'] = $name;
            }
            if ($file = $request->file('loader')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->loader);
                $input['loader'] = $name;
            }
            if ($file = $request->file('admin_loader')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->admin_loader);
                $input['admin_loader'] = $name;
            }
            if ($file = $request->file('affilate_banner')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->affilate_banner);
                $input['affilate_banner'] = $name;
            }
            if ($file = $request->file('error_banner')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->error_banner);
                $input['error_banner'] = $name;
            }
            if ($file = $request->file('popup_background')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->popup_background);
                $input['popup_background'] = $name;
            }
            if ($file = $request->file('invoice_logo')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->invoice_logo);
                $input['invoice_logo'] = $name;
            }
            if ($file = $request->file('user_image')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->user_image);
                $input['user_image'] = $name;
            }

            if ($file = $request->file('footer_logo')) {
                $name = time() . $file->getClientOriginalName();
                $data->upload($name, $file, $data->footer_logo);
                $input['footer_logo'] = $name;
            }

            $data->update($input);
            //--- Logic Section Ends


            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            //--- Redirect Section
            $msg = 'Data Updated Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
    }
    

    public function homesetting()
    {
        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        return view('admin.pageviewsetting.home', compact('colorsetting_style1', 'colorsetting_style2'));
    }


    public function homesetting_store(Request $request)
    {

        $data = Generalsetting::findOrFail(1);
        $setting = json_decode($data->product_view);
        $setting->home = $request->productview;
        $data->product_view = json_encode($setting);
        $data->update();
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    
    public function filteredsetting()
    {
        $colorsetting_style1 = ColorSetting::where('type', 2)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 2)->where('style_id', 2)->first();

        return view('admin.pageviewsetting.filtered', compact('colorsetting_style1', 'colorsetting_style2'));
    }


    public function filteredsetting_store(Request $request)
    {
        $data = Generalsetting::findOrFail(1);
        $setting = json_decode($data->product_view);
        $setting->filtered = $request->productview;
        $data->product_view = json_encode($setting);
        $data->update();
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    
    public function vendorsetting()
    {
        $colorsetting_style1 = ColorSetting::where('type', 3)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 3)->where('style_id', 2)->first();

        return view('admin.pageviewsetting.vendor', compact('colorsetting_style1', 'colorsetting_style2'));
    }


    public function vendorsetting_store(Request $request)
    {
        $data = Generalsetting::findOrFail(1);
        $setting = json_decode($data->product_view);
        $setting->product = $request->productview;
        $data->product_view = json_encode($setting);
        $data->update();
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function colorsetting(Request $request) {
        $type = $request->type;
        $style_id = $request->style_id;

        $colorsetting = ColorSetting::where('type', $type)
            ->where('style_id', $style_id)
            ->first();

        return view('admin.pageviewsetting.color', compact('type', 'style_id', 'colorsetting'));
    }

    public function colorsetting_store(Request $request) {
        $type = $request->type;
        $style_id = $request->style_id;

        $colorsetting = ColorSetting::updateOrCreate(
            ['type' => $request->type, 'style_id' => $request->style_id],
            [
                'title_color' => $request->title_color,
                'tag_bg_color' => $request->tag_bg_color,
                'tag_color' => $request->tag_color,
                'detail_color' => $request->detail_color,
                'sub_detail_color' => $request->sub_detail_color,
                'price_color' => $request->price_color,
                'buttons_color' => $request->buttons_color,
            ],
        );

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }
}
