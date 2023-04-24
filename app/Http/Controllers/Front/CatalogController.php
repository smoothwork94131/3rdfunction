<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Comment;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\AdvertisingProduct;
use App\Models\ProductClick;
use App\Models\ColorSetting;
use App\Models\Rating;
use App\Models\Reply;
use App\Models\Report;
use App\Models\Subcategory;
use App\Models\Generalsetting;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Session;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Classes\GeniusMailer;

class CatalogController extends Controller
{
    // -------------------------------- CATEGORY SECTION ----------------------------------------
    public function replaceDataToPath($path) {
        if(strstr($path, ":::")) {
            $path = str_replace(":::", "/", $path) ;
        }
        return $path ;
    }
    
    public function replacPathToData($data) {
        if(strstr($data, "/")) {
            $data = str_replace("/", ":::", $data) ;
        }
        return $data ;
    }

    public function category(Request $request, $category = null, $series = null, $model = null, $section = null, $group_id = null)
    {   
        $category = $this->replaceDataToPath($category) ;
        $series = $this->replaceDataToPath($series) ;
        $model = $this->replaceDataToPath($model) ;
        $section = $this->replaceDataToPath($section) ;
        $group_id = $this->replaceDataToPath($group_id) ;

        $slug_list = array("category"=>$category, "series"=>$series, "model"=>$model,  "section"=>$section, "group"=>$group_id ) ;
        
        if($section == "common") {
            $slug_list = array("category"=>$category, "series"=>$series, "model"=>$model) ;
        }

        $minprice = $request->min;
        $maxprice = $request->max;
        $search = $request->search;
        $db = strtolower($series);

        $prods = DB::table($db)
        ->select('*')
        ->when($minprice, function ($query, $minprice) {
            return $query->where('price', '>=', $minprice);
        })
        ->when($maxprice, function ($query, $maxprice) {
            return $query->where('price', '<=', $maxprice);
        });
    
        if ($search) {
            $search1 = ' ' . $search;
            $prods = $prods->where('name', 'like', '%' . $search . '%')->orWhere('name', 'like', $search1 . '%');
        }

        $prods = $prods->where('status', 1);

        if ($section) {
            if ($section == 'common') {
                $prods = $prods->where('best', 1)->where('subcategory_id', $model);
            } else {
                $prods = $prods->where('category_id', $group_id)->where('subcategory_id', $model);
            }
        }

        $prods = $prods->distinct();
        $prods = $prods->orderBy('top', 'asc');
        $prods = $prods->get();

        // dd($prods);

        $refno_flag = 0;
        $thumbnail_flag = 0;
        foreach($prods as $prod) {
            if($prod->top != 0) {
                $refno_flag = true;
            }

            if($prod->thumbnail != "") {
                $thumbnail_flag = true;
            }
        }
        
        $group = DB::table($db.'_categories')->where('group_Id', $group_id)->first();

        if($group) {
            $slug_list['group'] = $group->group_name ;
        }

        if($group && !file_exists(public_path('assets/images/group/'.$group->image)) && !file_exists(public_path('assets/images/group/'.$group->group_Id . '.png'))) {
            $gs = Generalsetting::findOrFail(1);
            if ($gs->is_smtp == 1) {
            
                $data = [
                    'to' => 'usamtg@hotmail.com',
                    'subject' => "No group image!!",
                    'body' => "Hello Admin!<br> There is no group image for: " . $group->group_name . " and " . $group->group_Id . ". <br> Please login to check. <br>Thank you.",
                ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $to = 'usamtg@hotmail.com';
                $subject = "No group image!!!!";
                $msg = "Hello Admin!<br> There is no group image for: " . $group->group_name . " and " . $group->group_Id . ". <br> Please login to check. <br>Thank you.";
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        }

        $data['db'] = $db;
        $data['prods'] = $prods;
        $data['group'] = $group;
        
        $data['model'] = $model ;
        $data['slug_list'] = $slug_list ;

        $data['refno_flag'] = $refno_flag;
        $data['thumbnail_flag'] = $thumbnail_flag;
        
        $colorsetting_style1 = ColorSetting::where('type', 2)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 2)->where('style_id', 2)->first();

        $data['colorsetting_style1'] = $colorsetting_style1;
        $data['colorsetting_style2'] = $colorsetting_style2;
        
        $data['db'] = $db;

        return view('front.category', $data);
    }

    public function collection(Request $request, $category = null, $series = null, $model = null, $section = null, $group_id = null)
    {   
        $category = $this->replaceDataToPath($category) ;
        $series = $this->replaceDataToPath($series) ;
        $model = $this->replaceDataToPath($model) ;
        $section = $this->replaceDataToPath($section) ;
        $group_id = $this->replaceDataToPath($group_id) ;

        $slug_list = array("category"=>$category, "series"=>$series, "model"=>$model,  "section"=>$section, "group"=>$group_id ) ;
        
        if($section == "common") {
            $slug_list = array("category"=>$category, "series"=>$series, "model"=>$model) ;
        }

        $minprice = $request->min;
        $maxprice = $request->max;
        $search = $request->search;
        $db = strtolower($series);

        $prods = DB::table($db)
        ->select('top', 'sku', 'price', 'name', 'thumbnail', 'id', 'product_type')
        ->when($minprice, function ($query, $minprice) {
            return $query->where('price', '>=', $minprice);
        })
        ->when($maxprice, function ($query, $maxprice) {
            return $query->where('price', '<=', $maxprice);
        });
    
        if ($search) {
            $search1 = ' ' . $search;
            $prods = $prods->where('name', 'like', '%' . $search . '%')->orWhere('name', 'like', $search1 . '%');
        }

        $prods = $prods->where('status', 1);

        if ($section) {
            if ($section == 'common') {
                $prods = $prods->where('best', 1)->where('subcategory_id', $model);
            } else {
                $prods = $prods->where('category_id', $group_id)->where('subcategory_id', $model);
            }
        }

        $prods = $prods->distinct();
        $prods = $prods->orderBy('top', 'asc');
        $prods = $prods->get();

        $refno_flag = 0;
        $thumbnail_flag = 0;
        foreach($prods as $prod) {
            if($prod->top != 0) {
                $refno_flag = true;
            }

            if($prod->thumbnail != "") {
                $thumbnail_flag = true;
            }
        }
        
        $group = DB::table($db.'_categories')->where('group_Id', $group_id)->first();

        if($group) {
            $slug_list['group'] = $group->group_name ;
        }

        if($group && !file_exists(public_path('assets/images/group/'.$group->image)) && !file_exists(public_path('assets/images/group/'.$group->group_Id . '.png'))) {
            $gs = Generalsetting::findOrFail(1);
            if ($gs->is_smtp == 1) {
            
                $data = [
                    'to' => 'usamtg@hotmail.com',
                    'subject' => "No group image!!",
                    'body' => "Hello Admin!<br> There is no group image for: " . $group->group_name . " and " . $group->group_Id . ". <br> Please login to check. <br>Thank you.",
                ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $to = 'usamtg@hotmail.com';
                $subject = "No group image!!!!";
                $msg = "Hello Admin!<br> There is no group image for: " . $group->group_name . " and " . $group->group_Id . ". <br> Please login to check. <br>Thank you.";
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        }

        $data['db'] = $db;
        $data['prods'] = $prods;
        $data['group'] = $group;
        
        $data['model'] = $model ;
        $data['slug_list'] = $slug_list ;

        $data['refno_flag'] = $refno_flag;
        $data['thumbnail_flag'] = $thumbnail_flag;

        $colorsetting_style1 = ColorSetting::where('type', 2)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 2)->where('style_id', 2)->first();

        $data['colorsetting_style1'] = $colorsetting_style1;
        $data['colorsetting_style2'] = $colorsetting_style2;
        
        $data['db'] = $db;

        if ($request->ajax()) {

            $data['ajax_check'] = 1;
            
            return view('includes.product.filtered-products', $data);
        }

        return view('front.category', $data);
    }

    public function getsubs(Request $request)
    {
        $category = Category::where('slug', $request->category)->firstOrFail();
        $subcategories = Subcategory::where('category_id', $category->id)->get();
        return $subcategories;
    }


    // -------------------------------- PRODUCT DETAILS SECTION ----------------------------------------

    public function report(Request $request)
    {
        //--- Validation Section
        $rules = [
            'note' => 'max:400',
        ];
        $customs = [
            'note.max' => 'Note Must Be Less Than 400 Characters.',
        ];
        $validator = Validator::make(  $request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Report;
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function product(Request $request, $category=null, $series=null, $model=null, $section=null, $group=null, $prod_name=null) {
        $category = $this->replaceDataToPath($category) ;
        $series = $this->replaceDataToPath($series) ;
        $model = $this->replaceDataToPath($model) ;
        $section = $this->replaceDataToPath($section) ;
        $group = $this->replaceDataToPath($group) ;
        $prod_name = $this->replaceDataToPath($prod_name) ;

        $db = strtolower($series);
        $group_record = DB::table($db.'_categories')
            ->where('group_name', $group)
            ->orWhere('group_Id', $group)
            ->first();
        
        $sql = "select * from `{$db}` where `subcategory_id`='{$model}' and `name` = '{$prod_name}' ;" ;

        $productt =DB::select($sql);
        if($productt && count($productt) > 0) {
            $productt = $productt[0] ;
        }
        else {
            $productt = null;
        }

        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $sql = "select * from `categories` where `parent` != 0 and `status` = 1 and `name` != '{$series}'" ;
        $tbl_info =DB::select($sql);

        $sql = "" ;
        $flag = false ;
        $arr_tbl = array();
        
        foreach($tbl_info as $item) {
            $arr_tbl[] = strtolower($item->name) ;
        }

        $fits = array();
        if($productt) {
            for($k = 0 ; $k < count($arr_tbl) ; $k++) {
                if($flag) {
                    $sql.=" union all " ;
                } 
                $sql .= "select distinct '$arr_tbl[$k]' as `table`, `subcategory_id` from `{$arr_tbl[$k]}` where `sku` = '{$productt->sku}' " ;
                $flag = true ;
            }
    
            $fits =DB::select($sql) ;        
        }
    
        $also_fits = array();

        foreach($fits as $item) {
            if(array_key_exists($item->table, $also_fits)) {
                $also_item = $also_fits[$item->table] ;
                array_push($also_item, $item->subcategory_id) ;
                $also_fits[$item->table] = $also_item ;
            } else {
                $also_fits[$item->table] = array($item->subcategory_id) ;
            }
        }

        $page = "partsbymodel" ;

        $slug_list = array("category"=>$category,"series"=>$series,"model"=>$model, "section"=>$this->replacPathToData($section), "group"=>$group, "prod_name"=>$this->replacPathToData($prod_name));
        return view('front.product', compact('productt', 'curr', 'group_record', 'colorsetting_style1', 'colorsetting_style2', "db", "page", "slug_list", "also_fits"));
    }

    public function homeproduct(Request $request, $slug)
    {   
        $this->code_image();
        $productt = Product::where('slug', '=', $slug)->firstOrFail();
        $productt->views += 1;
        $productt->update();
        
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $vendors = Product::where('status', '=', 1)->take(8)->get();

        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        $db="product" ;
        $page = "product" ; $slug_list = array("prod_name"=>$slug) ;

        return view('front.homeproduct', compact('productt', 'curr', 'vendors', 'colorsetting_style1', 'colorsetting_style2', "page", "slug_list"));
    }

    public function old_parts(Request $request, $query = null)
    {   
        $result = array();  
        $model = $this->replaceDataToPath($query) ;

        if(strstr($model, "5-finish")) {
            $sql = "select `sku` from `implements` where `featured` = 1 group by sku" ;
            $skus =collect(DB::select($sql));
            foreach($skus as $sku) {
                $sql1 = "select * from `implements` where `sku` = '{$sku->sku}'";
                $sku_record = DB::select($sql1);
                if($sku_record && count($sku_record) > 0) {
                    $result[] = $sku_record[0];
                }
            }
            $result = collect($result)->paginate(20);
        }
        else if(strstr($model, "6-finish")) {
            $sql = "select `sku` from `implements` where `latest` = 1 group by sku" ;
            $skus =collect(DB::select($sql));
            foreach($skus as $sku) {
                $sql1 = "select * from `implements` where `sku` = '{$sku->sku}'";
                $sku_record = DB::select($sql1);
                if($sku_record && count($sku_record) > 0) {
                    $result[] = $sku_record[0];
                }
            }
            $result = collect($result)->paginate(20);
        }
        else if(strstr($model, "7-finish")) {
            $sql = "select `sku` from `implements` where `big` = 1 group by sku" ;
            $skus =collect(DB::select($sql));
            foreach($skus as $sku) {
                $sql1 = "select * from `implements` where `sku` = '{$sku->sku}'";
                $sku_record = DB::select($sql1);
                if($sku_record && count($sku_record) > 0) {
                    $result[] = $sku_record[0];
                }
            }
            $result = collect($result)->paginate(20);
        }
        else if(strstr($model, "5-md")) {
            $sql = "select `sku` from `implements` where `trending` = 1 group by sku" ;
            $skus =collect(DB::select($sql));
            foreach($skus as $sku) {
                $sql1 = "select * from `implements` where `sku` = '{$sku->sku}'";
                $sku_record = DB::select($sql1);
                if($sku_record && count($sku_record) > 0) {
                    $result[] = $sku_record[0];
                }
            }
            $result = collect($result)->paginate(20);
        }
        else if(strstr($model, "6-md")) {
            $sql = "select `sku` from `implements` where `sale` = 1 group by sku" ;
            $skus =collect(DB::select($sql));
            foreach($skus as $sku) {
                $sql1 = "select * from `implements` where `sku` = '{$sku->sku}'";
                $sku_record = DB::select($sql1);
                if($sku_record && count($sku_record) > 0) {
                    $result[] = $sku_record[0];
                }
            }
            $result = collect($result)->paginate(20);
        }
        else if(strstr($model, "additonal-products")) {
            $sql = "select * from products_additional";
            $result = collect(DB::select($sql))->paginate(20);
        }
        else {
            if(strstr($model, "mahindra")) {
                $model = str_replace("mahindra-", "", $model) ;
            }
            
            if(strstr($model, "-")) {
                $model = str_replace("-", " ", $model) ;
            }
    
            $sql = "select * from `models` where model_name like '%". $model ."%'";
            $model_info =DB::select($sql);
            $flag = false;
            $sql = "";
            
            foreach($model_info as $model_record) {
                if($flag) {
                    $sql.=" union all " ;
                } 
                $sql .= "select distinct '$model_record->table_name' as `table`, `sku`, `subcategory_id`, `category_id`, `name`, `photo`, `stock`, `product_condition`, `youtube`, `type`, `region`, `platform`, `size`, `size_qty`, `size_price`, `price`, `id`, `product_type`, `ship`, `description`, `policy`, `meta_description`, `thumbnail` from `{$model_record->table_name}` where `subcategory_id` = '{$model_record->model_name}'" ;
                $flag = true ;
            }

            $result =collect(DB::select($sql))->paginate(20);
        }
        
        // dd($result);
        $slug_list = array("model"=>$model);
        return view('front.oldparts', compact('result', "slug_list"));
    }

    public function old_collection(Request $request, $model = null, $prod_name = null)
    {   
        $productt = false;  
        
        if(strstr($model, "mahindra")) {
            $model = str_replace("mahindra-", "", $model) ;
        }
        
        if(strstr($model, "-")) {
            $model = str_replace("-", " ", $model) ;
        }

        if($model == "additonal products") {
            $prod_name_arr = explode('-', $prod_name);
            $sku = $prod_name_arr[0];
            unset($prod_name_arr[0]);
            $prod_name = implode(' ', $prod_name_arr);

            $sql = "select * from `products_additional` where `sku`='{$sku}' and `name`='{$prod_name}'";
            $productt =DB::select($sql);
            if($productt && count($productt) > 0) {
                $productt = $productt[0] ;
            }
            else {
                $productt = null;
            }
        }
        else {
            $sql = "select * from `models` where model_name like '%". $model ."%'";
            $model_info =DB::select($sql);

            if($model_info && count($model_info) > 0) {
                $table_name = $model_info[0]->table_name;
                $model = $model_info[0]->model_name;

                $prod_name_arr = explode('-', $prod_name);
                $sku = $prod_name_arr[0];

                $sql2 = "select distinct '$table_name' as `table`, `sku`, `subcategory_id`, `category_id`, `name`, `photo`, `thumbnail`, `stock`, `product_condition`, `youtube`, `type`, `region`, `platform`, `size`, `size_qty`, `size_price`, `price`, `id`, `product_type`, `ship`, `description`, `policy`, `meta_description`, `thumbnail` from `{$table_name}` where `subcategory_id` = '{$model}' and `sku`='{$sku}'";

                $productt =DB::select($sql2);
                if($productt && count($productt) > 0) {
                    $productt = $productt[0] ;
                }
                else {
                    $cnt = count($prod_name_arr);
                    $sku = $prod_name_arr[$cnt-1];

                    $sql2 = "select distinct '$table_name' as `table`, `sku`, `subcategory_id`, `category_id`, `name`, `photo`, `thumbnail`,  `stock`, `product_condition`, `youtube`, `type`, `region`, `platform`, `size`, `size_qty`, `size_price`, `price`, `id`, `product_type`, `ship`, `description`, `policy`, `meta_description`, `thumbnail` from `{$table_name}` where `subcategory_id` = '{$model}' and `sku`='{$sku}'";

                    $productt =DB::select($sql2);
                    if($productt && count($productt) > 0) {
                        $productt = $productt[0] ;
                    }
                    else {
                        $productt = null;
                    }
                }
            }
            else {
                $productt = null;
            }
        }

        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $page = "partsbymodel" ;

        $slug_list = array("model"=>$model, "prod_name"=>$this->replacPathToData($prod_name));
        return view('front.oldproduct', compact('productt', 'curr', 'colorsetting_style1', 'colorsetting_style2', "page", "slug_list"));
    }

    public function searchProdDetail() {
        $this->code_image();
        $productt = Product::where('slug', '=', $slug)->firstOrFail();
        $productt->views += 1;
        $productt->update();
        
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $group_record->image = "";

        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        $db="product" ;
        $page = "" ;
        $slug_list = array() ;
        return view('front.product', compact('productt', 'curr', 'group_record', 'colorsetting_style1', 'colorsetting_style2', "page", "slug_list"));
    }

    // Capcha Code Image
    private function code_image()
    {
        $actual_path = base_path();
        
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        $pixel = imagecolorallocate($image, 0, 0, 255);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
        }

        $font = $actual_path . '/public/assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length - 1)];
        $word = '';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length = 6;// No. of character in image
        for ($i = 0; $i < $cap_length; $i++) {
            $letter = $allowed_letters[rand(0, $length - 1)];
            imagettftext($image, 25, 1, 35 + ($i * 25), 35, $text_color, $font, $letter);
            $word .= $letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path . "/public/assets/images/capcha_code.png");
    }

    public function quick($id)
    {
        
        $product = Product::findOrFail($id);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
    
        return view('load.quick', compact('product', 'curr'));

    }

    public function iquick($db, $id)
    {
        $product = DB::table($db)->find($id);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        return view('load.quick', compact('product', 'curr', 'db'));
    }

    // -------------------------------- PRODUCT DETAILS SECTION ENDS----------------------------------------

    // ------------------ Rating SECTION --------------------

    public function reviewsubmit(Request $request)
    {
        $ck = 0;
        $orders = Order::where('user_id', '=', $request->user_id)->where('status', '=', 'completed')->get();

        foreach ($orders as $order) {
            $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
            foreach ($cart->items as $product) {
                if ($request->product_id == $product['item']['id']) {
                    $ck = 1;
                    break;
                }
            }
        }
        if ($ck == 1) {
            $user = Auth::guard('web')->user();
            $prev = Rating::where('product_id', '=', $request->product_id)->where('user_id', '=', $user->id)->get();
            if (count($prev) > 0) {
                return response()->json(array('errors' => [0 => 'You Have Reviewed Already.']));
            }
            $Rating = new Rating;
            $Rating->fill($request->all());
            $Rating['review_date'] = date('Y-m-d H:i:s');
            $Rating->save();
            $data[0] = 'Your Rating Submitted Successfully.';
            $data[1] = Rating::rating($request->product_id);
            return response()->json($data);
        } else {
            return response()->json(array('errors' => [0 => 'Buy This Product First']));
        }
    }


    public function reviews($id)
    {
        $productt = Product::find($id);
        return view('load.reviews', compact('productt', 'id'));

    }

    // ------------------ Rating SECTION ENDS --------------------
}
