<?php
namespace App\Http\Controllers\Front;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\PaymentGateway;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use App\Models\TempCart;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;
use Validator;
use PHPShopify\ShopifySDK;
class CheckoutController extends Controller
{
    public function loadpayment($slug1, $slug2)
    {
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }
        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }
    public function checkout()
    {
        $this->code_image();
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any products to checkout.");
        }
        $gs = Generalsetting::findOrFail(1);
        $dp = 1;
        $vendor_shipping_id = 0;
        $vendor_packing_id = 0;
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
            // If a user is Authenticated then there is no problm user can go for checkout
        if (Auth::guard('web')->check()) {
            $gateways = PaymentGateway::where('status', '=', 1)->get();
            $pickups = Pickup::all();
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            $products = $cart->items;
            // Shipping Method
            if ($gs->multiple_shipping == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']->user_id;
                }

                $users = array_unique($user);
                if (count($users) == 1) {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                    if (count($shipping_data) == 0) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_shipping_id = $users[0];
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }

            } else {
                $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
            }
            // Packaging
            if ($gs->multiple_packaging == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']->user_id;
                }
                $users = array_unique($user);
                if (count($users) == 1) {
                    $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                    if (count($package_data) == 0) {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_packing_id = $users[0];
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
            } else {
                $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
            }

            // foreach ($products as $prod) {
            //     if ($prod['item']->type == 'Physical') {
            //         $dp = 0;
            //         break;
            //     }
            // }

            if ($dp == 1) {
                $ship = 0;
            }

            $total = 0;
            $productList = [];
            $productListNoWeight = [];
            foreach ($products as $prod) {
                if ($prod['item']->file) {
                    $total += $prod['item']->price;
                    array_push($productList, $prod);
                } else {
                    array_push($productListNoWeight, $prod);
                }
            }

            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if ($gs->tax != 0) {
                $tax = ($total / 100) * $gs->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = $total + round(0 * $curr->value, 2);
            }

            return view('front.checkout', ['products' => $productList, 'productsNw' => $productListNoWeight, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
        } else {
            // If guest checkout is activated then user can go for checkout
            if ($gs->guest_checkout == 1) {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;
                // Shipping Method
                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                // Packaging
                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
                foreach ($products as $prod) {
                    if ($prod['item']->type == 'Physical') {
                        $dp = 0;
                        break;
                    }
                }
                $productList = [];
                $productListNoWeight = [];
                $total = 0;
                foreach ($products as $prod) {
                    if ($prod['item']->file) {
                        $total += $prod['item']->price;
                        array_push($productList, $prod);
                    } else {
                        array_push($productListNoWeight, $prod);
                    }
                }
                if ($dp == 1) {
                    $ship = 0;
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
                }
                foreach ($products as $prod) {
                    if ($prod['item']->type != 'Physical') {
                        if (!Auth::guard('web')->check()) {
                            $ck = 1;
                            return view('front.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
                        }
                    }
                }
                return view('front.checkout', ['products' => $productList, 'productsNw' => $productListNoWeight, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
            } // If guest checkout is Deactivated then display pop up form with proper error message
            else {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;
                // Shipping Method
                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                // Packaging
                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = $total + round(0 * $curr->value, 2);
                }
                $ck = 1;
                return view('front.checkout', ['products' => $cart->items, 'productsNw' => [], 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
            }
        }
    }
    
    public function checkouttemp($id)
    {
        $this->code_image();
        $tempcart = TempCart::findOrFail($id);
        $gs = Generalsetting::findOrFail(1);
        $dp = 1;
        $vendor_shipping_id = 0;
        $vendor_packing_id = 0;
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        // If a user is Authenticated then there is no problm user can go for checkout
        if (Auth::guard('web')->check()) {
            $gateways = PaymentGateway::where('status', '=', 1)->get();
            $pickups = Pickup::all();
            $cart = json_decode($tempcart->content);
            foreach($cart->items as $key=>$value) {
                $cart->items->$key = (array)$cart->items->$key;
            }
            $products = $cart->items;
            // Shipping Method
            if ($gs->multiple_shipping == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']->user_id;
                }
                $users = array_unique($user);
                if (count($users) == 1) {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                    if (count($shipping_data) == 0) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_shipping_id = $users[0];
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
            } else {
                $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
            }
            // Packaging
            if ($gs->multiple_packaging == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']->user_id;
                }
                $users = array_unique($user);
                if (count($users) == 1) {
                    $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                    if (count($package_data) == 0) {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_packing_id = $users[0];
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
            } else {
                $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
            }
            foreach ($products as $prod) {
                if ($prod['item']->type == 'Physical') {
                    $dp = 0;
                    break;
                }
            }
            if ($dp == 1) {
                $ship = 0;
            }
            $total = 0;
            $productList = [];
            $productListNoWeight = [];
            foreach ($products as $prod) {
                if ($prod['item']->file) {
                    $total += $prod['item']->price;
                    array_push($productList, $prod);
                } else {
                    array_push($productListNoWeight, $prod);
                }
            }
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if ($gs->tax != 0) {
                $tax = ($total / 100) * $gs->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = $total + round(0 * $curr->value, 2);
            }
            return view('front.checkout', ['products' => $productList, 'productsNw' => $productListNoWeight, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
        } else {
            // If guest checkout is activated then user can go for checkout
            if ($gs->guest_checkout == 1) {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;
                // Shipping Method
                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                // Packaging
                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
                foreach ($products as $prod) {
                    if ($prod['item']->type == 'Physical') {
                        $dp = 0;
                        break;
                    }
                }
                if ($dp == 1) {
                    $ship = 0;
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
                }
                foreach ($products as $prod) {
                    if ($prod['item']->type != 'Physical') {
                        if (!Auth::guard('web')->check()) {
                            $ck = 1;
                            return view('front.checkout', ['products' => $cart->items,'productsNw' => [], 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
                        }
                    }
                }
                return view('front.checkout', ['products' => $cart->items, 'productsNw' => [], 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
            } // If guest checkout is Deactivated then display pop up form with proper error message
            else {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;
                // Shipping Method
                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                // Packaging
                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']->user_id;
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = $total + round(0 * $curr->value, 2);
                }
                $ck = 1;
                return view('front.checkout', ['products' => $cart->items, 'productsNw' => [], 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
            }
        }
    }
    public function cashondelivery(Request $request) {
        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exists.");
            }
        }
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any products to checkout.");
        }
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $gs = Generalsetting::findOrFail(1);
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
       
        
        foreach ($cart->items as $key => $prod) {
           
            if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                    if ($dtl != 0) {
                        $dtl--;
                        $produc = Product::findOrFail($prod['item']['id']);
                        $temp = $produc->license_qty;
                        $temp[$ttl] = $dtl;
                        $final = implode(',', $temp);
                        $produc->license_qty = $final;
                        $produc->update();
                        $temp = $produc->license;
                        $license = $temp[$ttl];
                        $oldCart = Session::has('cart') ? Session::get('cart') : null;
                        $cart = new Cart($oldCart);
                        $cart->updateLicense($prod['item']['id'], $license);
                        Session::put('cart', $cart);
                        break;
                    }
                }
            }
        }
        $order = new Order;
        $success_url = action('Front\PaymentController@payreturn');
        $item_name = $gs->title . " Order";
        $item_number = str_random(4) . time();
        $order['user_id'] = $request->user_id;
        $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
        $order['totalQty'] = $request->totalQty;
        $order['pay_amount'] = round($request->total / $curr->value, 2);
        $order['method'] = $request->method;
        $order['shipping'] = $request->shipping;
        $order['pickup_location'] = $request->pickup_location;
        $order['customer_email'] = $request->email;
        $order['customer_name'] = $request->name;
        $order['shipping_cost'] = $request->shipping_cost;
        $order['packing_cost'] = $request->packing_cost;
        $order['tax'] = $request->tax;
        $order['customer_phone'] = $request->phone;
        $order['order_number'] = str_random(4) . time();
        $order['customer_address'] = $request->address;
        $order['customer_country'] = $request->customer_country;
        $order['customer_city'] = $request->city;
        $order['customer_zip'] = $request->zip;
        $order['shipping_email'] = $request->shipping_email;
        $order['shipping_name'] = $request->shipping_name;
        $order['shipping_phone'] = $request->shipping_phone;
        $order['shipping_address'] = $request->shipping_address;
        $order['shipping_country'] = $request->shipping_country;
        $order['shipping_city'] = $request->shipping_city;
        $order['shipping_zip'] = $request->shipping_zip;
        $order['order_note'] = $request->order_notes;
        $order['coupon_code'] = $request->coupon_code;
        $order['coupon_discount'] = $request->coupon_discount;
        $order['dp'] = $request->dp;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;
        $order['vendor_shipping_id'] = $request->vendor_shipping_id;
        $order['vendor_packing_id'] = $request->vendor_packing_id;
        if (Session::has('affilate')) {
            $val = $request->total / $curr->value;
            $val = $val / 100;
            $sub = $val * $gs->affilate_charge;
            $user = User::findOrFail(Session::get('affilate'));
            $user->affilate_income += $sub;
            $user->update();
            $order['affilate_user'] = $user->name;
            $order['affilate_charge'] = $sub;
        }
        $order->save();
        $track = new OrderTrack;
        $track->title = 'Pending';
        $track->text = 'You have successfully placed your order.';
        $track->order_id = $order->id;
        $track->save();
        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();
        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if ($coupon->times != null) {
                $i = (int)$coupon->times;
                $i--;
                $coupon->times = (string)$i;
            }
            $coupon->update();
        }
        foreach ($cart->items as $prod) {
            $x = (string)$prod['size_qty'];
            if (!empty($x)) {
                $product = Product::findOrFail($prod['item']['id']);
                $x = (int)$x;
                $x = $x - $prod['qty'];
                $temp = $product->size_qty;
                $temp[$prod['size_key']] = $x;
                $temp1 = implode(',', $temp);
                $product->size_qty = $temp1;
                $product->update();
            }
        }
        foreach ($cart->items as $prod) {
            $x = (string)$prod['stock'];
            if ($x != null) {
                $product = Product::findOrFail($prod['item']['id']);
                $product->stock = $prod['stock'];
                $product->update();
                if ($product->stock <= 5) {
                    $notification = new Notification;
                    $notification->product_id = $product->id;
                    $notification->save();
                }
            }
        }
        $notf = null;
        foreach ($cart->items as $prod) {
            if ($prod['item']->user_id != 0) {
                $vorder = new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod['item']->user_id;
                $notf[] = $prod['item']->user_id;
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $order->order_number;
                $vorder->save();
            }
        }
        if (!empty($notf)) {
            $users = array_unique($notf);
            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();
            }
        }
        Session::put('temporder', $order);
        Session::put('tempcart', $cart);
        Session::forget('cart');
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');
        //Sending Email To Buyer
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $request->email,
                'type' => "new_order",
                'cname' => $request->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data, $order->id);
        } else {
            $to = $request->email;
            $subject = "Your Order Placed!!";
            $msg = "Hello " . $request->name . "!\nYou have placed a new order.\nYour order number is " . $order->order_number . ". Thank you for your business. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        //Sending Email To Admin
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is " . $order->order_number . ". Please login to the dashboard to check. <br>Thank you.",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $to = Pagesetting::find(1)->contact_email;
            $subject = "New Order Recieved!!";
            $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is " . $order->order_number . ". Please login to the dashboard to check. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        return redirect($success_url);
    }
    public function shopifycheckout(Request $request) {
        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $user->phone = $request->phone;
                    $user->address = $request->address;
                    $user->city = $request->city;
                    $user->country = $request->customer_country;
                    $user->zip = $request->zip;
                    
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exists.");
            }
        }

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any products to checkout.");
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $gs = Generalsetting::findOrFail(1);
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        
    
        $shop_url = env('SHOPIFY_SHOP_URL', '');
        $storefrontAccessToken = env('SHOPIFY_FRONTSTORE_ACCESS_TOKEN', '');
        $storeAccessToken = env('SHOPIFY_ACCESS_TOKEN', '');
        $shopify_api_version = env('SHOPIFY_API_VERSION', '2023-01');
        
        $config = array(
            'ShopUrl' => $shop_url,
            'AccessToken' => $storeAccessToken,
            'FrontAccessToken' => $storefrontAccessToken,
            'ApiVersion' => $shopify_api_version
        );

        $shopify = ShopifySDK::config($config);
        
        $input = '{
            allowPartialAddresses: true,
            buyerIdentity: {
              countryCode: US
            },
            customAttributes: {
                key: "email",
                value: "' . $request->personal_email . '"
            },
            email: "'.$request->personal_email.'",
            note: "'.$request->order_notes.'",
            shippingAddress: {
                address1: "'.($request->shipping_address ?? $request->address).'",
                address2: "",
                city: "'.($request->shipping_city ?? $request->city).'",
                company: "",
                country: "'.($request->shipping_country ?? $request->customer_country).'",
                firstName: "'.($request->shipping_name ?? $request->name).'",
                lastName: "",
                phone: "'.($request->shipping_phone ?? $request->phone).'",
                province: "PA",
                zip: "'.($request->shipping_zip ?? $request->zip).'"
              }
            lineItems: [';
        
        try {
            $i = 0;
            $count = count($cart->items);
            $needToTemp = false;
            foreach ($cart->items as $key => $prod) {
                if (!$prod['item']->file) {
                    $needToTemp = true;
                    continue;
                }
                $i++;
                
                $query = '{
                    products(first: 1, query:"(title:'.$prod['item']->name.') AND (variants.sku:'.$prod['item']->sku.')",) {
                        edges {
                            node {
                                variants(first: 5) {
                                    edges {
                                        node {
                                            id
                                        }
                                    }
                                }
                            }
                        }
                    }
                }';
                
                $productFromShopify = $shopify->GraphQL->post($query);      

                if ($productFromShopify['data']['products']['edges']) {

                    $update_input = '{
                        id: "'. $productFromShopify['data']['products']['edges'][0]['node']['variants']['edges'][0]['node']['id'] .'",
                        price: '. $prod['item']->price .'
                    }';
    
                    $update_query = <<<QUERY
                    mutation {
                        productVariantUpdate( input: {$update_input}) {
                            productVariant {
                                id
                                title
                                inventoryPolicy
                                inventoryQuantity
                                price
                                compareAtPrice
                            }
                            userErrors {
                                field
                                message
                            }
                        }
                    }
                    QUERY;
    
                    $graphql_url = "https://" . $shop_url . "/admin/api/". $shopify_api_version ."/graphql.json";
                    
                    $post_data = array();
                    $post_data['query'] = $update_query;
                    $curl_init = curl_init();
                    curl_setopt($curl_init, CURLOPT_URL, $graphql_url);
                    curl_setopt($curl_init, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json', 
                        'Accept: application/json', 
                        'X-Shopify-Storefront-Access-Token: '. $storefrontAccessToken, 
                        'X-Shopify-Access-Token: '. $storeAccessToken
                    ));
                    curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl_init, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($curl_init, CURLOPT_POSTFIELDS, json_encode($post_data));
                    curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec ($curl_init);
                    $product_info = json_decode($response, true);
                    $curl_info = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
                    curl_close ($curl_init);
    
                    // print_r($product_info); exit;

                    if($i == $count) {
                        $input .= "{
                            quantity: {$prod['qty']},
                            variantId: \"{$productFromShopify['data']['products']['edges'][0]['node']['variants']['edges'][0]['node']['id']}\"
                        }";
                    }
                    else {
                        $input .= "{
                            quantity: {$prod['qty']},
                            variantId: \"{$productFromShopify['data']['products']['edges'][0]['node']['variants']['edges'][0]['node']['id']}\"
                        },";
                    }
                } else {
                    $this->createProductOnShopify($prod);
                }
                
                $cart->removeItem($key);
            }

            $input.=']}';
            
            if ($needToTemp) {
                $content = [
                    'totalQty' => $cart->totalQty,
                    'totalPrice' => $cart->totalPrice,
                    'items' => $cart->items
                ];
                $tempcart = new TempCart;
                $tempcart->content = json_encode($content);
                $tempcart->user_email = $request->email;
                $tempcart->save();
                $to = 'usamtg@hotmail.com';
                $subject = 'No Weight Alert';
                $msg = "A customer has tried to buy no weight products, <a href=" . url('admin/tempcart/edit')."/". $tempcart->id . "> click here to review:</a>";
                //Sending Email To Customer
                if ($gs->is_smtp == 1) {
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
            }

            if ($i == 0) {
                Session::put('tempcart', $cart);
                Session::forget('cart');
                Session::forget('already');
                Session::forget('coupon');
                Session::forget('coupon_total');
                Session::forget('coupon_total1');
                Session::forget('coupon_percentage');
                return redirect()->route('front.index');
            }

            $query = <<<QUERY
            mutation {
                checkoutCreate(input: {$input}) {
                    checkout {
                    id
                    webUrl
                    }
                    checkoutUserErrors {
                    field
                    message
                    }
                }
            }
            QUERY;

            $graphql_url = "https://" . $shop_url . "/api/". $shopify_api_version ."/graphql.json";
            $post_data = array();
            $post_data['query'] = $query;
            $curl_init = curl_init();
            curl_setopt($curl_init, CURLOPT_URL, $graphql_url);
            curl_setopt($curl_init, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json', 
                'Accept: application/json', 
                'X-Shopify-Storefront-Access-Token: '. $storefrontAccessToken, 
                'X-Shopify-Access-Token: '. $storeAccessToken
            ));
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_init, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl_init, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec ($curl_init);
            $checkoutsh = json_decode($response, true);

            $curl_info = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
            curl_close ($curl_init);

            // $checkoutsh = $shopify->GraphQL->post();

            if ($checkoutsh['data']['checkoutCreate']['checkout']['webUrl']) {
                Session::put('tempcart', $cart);
                Session::forget('cart');
                Session::forget('already');
                Session::forget('coupon');
                Session::forget('coupon_total');
                Session::forget('coupon_total1');
                Session::forget('coupon_percentage');
                return redirect($checkoutsh['data']['checkoutCreate']['checkout']['webUrl']);
            } else {
                Session::put('tempcart', $cart);
                Session::forget('cart');
                Session::forget('already');
                Session::forget('coupon');
                Session::forget('coupon_total');
                Session::forget('coupon_total1');
                Session::forget('coupon_percentage');
                return redirect()->route('front.index')->with('success', "Something went wrong. Try again later!");
            }
        } catch (\Exception $e) {

            echo $e->getMessage(); exit;
            Session::put('tempcart', $cart);
            Session::forget('cart');
            Session::forget('already');
            Session::forget('coupon');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('coupon_percentage');
            return redirect()->route('front.index')->with('success', "Something went wrong. Try again later!");
        }
    }

    public function addToTemp(Request $request) {
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any products to checkout.");
        }
        $gs = Generalsetting::findOrFail(1);
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // $storefrontAccessToken = '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';
        $content = [
            'totalQty' => $cart->totalQty,
            'totalPrice' => $cart->totalPrice,
            'items' => $cart->items
        ];
        $tempcart = new TempCart;
        $tempcart->content = json_encode($content);
        $tempcart->user_email = $request->email;
        $tempcart->save();
        $to = 'usamtg@hotmail.com';
        $subject = 'No Weight Alert';
        $msg = "A customer has tried to buy no weight products, <a href=" . url('admin/tempcart/edit')."/". $tempcart->id . "> click here to review:</a>";
        //Sending Email To Customer
        if ($gs->is_smtp == 1) {
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
        Session::put('tempcart', $cart);
        Session::forget('cart');
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');
        $email = $request->email;
        $message = 'Thank you for your business. We will notify you via an email to '.$email.' when your order is ready and you can finish your checkout.';
        return view('front.success', compact('message', 'email'));
    }

    public function gateway(Request $request)
    {
        $input = $request->all();
        $rules = [
            'txn_id4' => 'required',
        ];
        $messages = [
            'required' => 'The Transaction ID field is required.',
        ];
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            Session::flash('unsuccess', $validator->messages()->first());
            return redirect()->back()->withInput();
        }
        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exists.");
            }
        }
        $gs = Generalsetting::findOrFail(1);
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any products to checkout.");
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        foreach ($cart->items as $key => $prod) {
            if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                    if ($dtl != 0) {
                        $dtl--;
                        $produc = Product::findOrFail($prod['item']['id']);
                        $temp = $produc->license_qty;
                        $temp[$ttl] = $dtl;
                        $final = implode(',', $temp);
                        $produc->license_qty = $final;
                        $produc->update();
                        $temp = $produc->license;
                        $license = $temp[$ttl];
                        $oldCart = Session::has('cart') ? Session::get('cart') : null;
                        $cart = new Cart($oldCart);
                        $cart->updateLicense($prod['item']['id'], $license);
                        Session::put('cart', $cart);
                        break;
                    }
                }
            }
        }
        $settings = Generalsetting::findOrFail(1);
        $order = new Order;
        $success_url = action('Front\PaymentController@payreturn');
        $item_name = $settings->title . " Order";
        $item_number = str_random(4) . time();
        $order['user_id'] = $request->user_id;
        $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
        $order['totalQty'] = $request->totalQty;
        $order['pay_amount'] = round($request->total / $curr->value, 2);
        $order['method'] = $request->method;
        $order['shipping'] = $request->shipping;
        $order['pickup_location'] = $request->pickup_location;
        $order['customer_email'] = $request->email;
        $order['customer_name'] = $request->name;
        $order['shipping_cost'] = $request->shipping_cost;
        $order['packing_cost'] = $request->packing_cost;
        $order['tax'] = $request->tax;
        $order['customer_phone'] = $request->phone;
        $order['order_number'] = str_random(4) . time();
        $order['customer_address'] = $request->address;
        $order['customer_country'] = $request->customer_country;
        $order['customer_city'] = $request->city;
        $order['customer_zip'] = $request->zip;
        $order['shipping_email'] = $request->shipping_email;
        $order['shipping_name'] = $request->shipping_name;
        $order['shipping_phone'] = $request->shipping_phone;
        $order['shipping_address'] = $request->shipping_address;
        $order['shipping_country'] = $request->shipping_country;
        $order['shipping_city'] = $request->shipping_city;
        $order['shipping_zip'] = $request->shipping_zip;
        $order['order_note'] = $request->order_notes;
        $order['txnid'] = $request->txn_id4;
        $order['coupon_code'] = $request->coupon_code;
        $order['coupon_discount'] = $request->coupon_discount;
        $order['dp'] = $request->dp;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;
        $order['vendor_shipping_id'] = $request->vendor_shipping_id;
        $order['vendor_packing_id'] = $request->vendor_packing_id;
        if (Session::has('affilate')) {
            $val = $request->total / $curr->value;
            $val = $val / 100;
            $sub = $val * $gs->affilate_charge;
            $user = User::findOrFail(Session::get('affilate'));
            $user->affilate_income += $sub;
            $user->update();
            $order['affilate_user'] = $user->name;
            $order['affilate_charge'] = $sub;
        }
        $order->save();
        $track = new OrderTrack;
        $track->title = 'Pending';
        $track->text = 'You have successfully placed your order.';
        $track->order_id = $order->id;
        $track->save();
        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();
        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if ($coupon->times != null) {
                $i = (int)$coupon->times;
                $i--;
                $coupon->times = (string)$i;
            }
            $coupon->update();
        }
        foreach ($cart->items as $prod) {
            $x = (string)$prod['size_qty'];
            if (!empty($x)) {
                $product = Product::findOrFail($prod['item']['id']);
                $x = (int)$x;
                $x = $x - $prod['qty'];
                $temp = $product->size_qty;
                $temp[$prod['size_key']] = $x;
                $temp1 = implode(',', $temp);
                $product->size_qty = $temp1;
                $product->update();
            }
        }
        foreach ($cart->items as $prod) {
            $x = (string)$prod['stock'];
            if ($x != null) {
                $product = Product::findOrFail($prod['item']['id']);
                $product->stock = $prod['stock'];
                $product->update();
                if ($product->stock <= 5) {
                    $notification = new Notification;
                    $notification->product_id = $product->id;
                    $notification->save();
                }
            }
        }
        $notf = null;
        foreach ($cart->items as $prod) {
            if ($prod['item']->user_id != 0) {
                $vorder = new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod['item']->user_id;
                $notf[] = $prod['item']->user_id;
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $order->order_number;
                $vorder->save();
            }
        }
        if (!empty($notf)) {
            $users = array_unique($notf);
            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();
            }
        }
        Session::put('temporder', $order);
        Session::put('tempcart', $cart);
        Session::forget('cart');
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');
        //Sending Email To Buyer
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $request->email,
                'type' => "new_order",
                'cname' => $request->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data, $order->id);
        } else {
            $to = $request->email;
            $subject = "Your Order Placed!!";
            $msg = "Hello " . $request->name . "!\nYou have placed a new order.\nYour order number is " . $order->order_number . ". Please wait for your delivery. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        //Sending Email To Admin
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is " . $order->order_number . ". Please login to the dashboard to check. <br>Thank you.",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $to = Pagesetting::find(1)->contact_email;
            $subject = "New Order Recieved!!";
            $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is " . $order->order_number . ". Please login to the dashboard to check. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        return redirect($success_url);
    }
    // Capcha Code Image
    private function code_image()
    {
        $actual_path = str_replace('project', '', base_path());
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

    private function createProductOnShopify($prod) {

        $shop_url = env('SHOPIFY_SHOP_URL', '');
        $storefrontAccessToken = env('SHOPIFY_FRONTSTORE_ACCESS_TOKEN', '');
        $storeAccessToken = env('SHOPIFY_ACCESS_TOKEN', '');
        $shopify_api_version = env('SHOPIFY_API_VERSION', '2023-01');
        
        $adminConfig = array(
            'ShopUrl' => $shop_url,
            'AccessToken' => $storeAccessToken,
            'FrontAccessToken' => $storefrontAccessToken,
            'ApiVersion' => $shopify_api_version
        );
        
        $adminshopify = ShopifySDK::config($adminConfig);
        
        $input = '{
            title: "'.$prod['item']->name.'", 
            descriptionHtml: "'.$prod['item']->name.'", 
            vendor: "Tractor Brothers",
            variants: [
                {
                    sku: "'.$prod['item']->sku.'",
                    weight: '.$prod['item']->file.'
                    price: '.$prod['item']->price.'
                }
            ]
        }';

        $checkoutsh = $adminshopify->GraphQL->post(<<<QUERY
        mutation {
            productCreate(input: {$input}) {
                product {
                    id
                }
            }
        }
        QUERY,);
    }
}