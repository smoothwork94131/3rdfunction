<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\TempCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Generalsetting;
use App\Classes\GeniusMailer;
use Auth;
use PHPShopify\ShopifySDK;

class TempCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = TempCart::orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('details', function (TempCart $data) {
                $details = mb_strlen(strip_tags($data->details), 'utf-8') > 250 ? mb_substr(strip_tags($data->details), 0, 250, 'utf-8') . '...' : strip_tags($data->details);
                return $details;
            })
            ->addColumn('action', function (TempCart $data) {
                return '<div class="action-list"><a data-href="' . route('admin-faq-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-faq-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.faq.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.faq.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $data = new TempCart();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($id)
    {
        $data = TempCart::findOrFail($id);
        $cart = json_decode($data->content);
        return view('admin.tempcart.edit', compact('data', 'cart'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $cart = TempCart::findOrFail($id);
        $cartContent = json_decode($cart->content);
        $admin = Auth::guard('admin')->user();

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


        foreach ($data as $key => $value) {
            if ($key !== '_token') {
                $cartContent->items->$key->item->file = $value;
                DB::table($cartContent->items->$key->db)
                ->where('id',$cartContent->items->$key->item->id)
                ->update([
                    'file' => $value,
                ]);

                $query = '{
                    products(first: 1, query:"(title:'.$cartContent->items->$key->item->name.') AND (variants.sku:'.$cartContent->items->$key->item->sku.')",) {
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

                $id = $productFromShopify['data']['products']['edges'][0]['node']['variants']['edges'][0]['node']['id'];

                $input = '{
                    id: "'.$id.'",
                    weight: '.$value.'
                }';


                $updatesh = $shopify->GraphQL->post(<<<QUERY
                    mutation {
                        productVariantUpdate(input: {$input}) {
                            product {
                               id
                            }
                            productVariant {
                                id
                            }
                            userErrors {
                                field
                                message
                            }
                        }
                    }
                    QUERY,);

            }
        }

        $cart->content = json_encode($cartContent);
        $cart->update();

        $to = $cart->user_email;
        $subject = 'Your temp cart is updated. Please try to checkout';
        $from = $admin->email;
        $msg = "Email: " . $from . "<br>Message: <a href=" . url('checkouttemp')."/". $$cart->id .">click here to review:</a>";
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_smtp == 1) {

            $datas = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($datas);
        } else {
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        return redirect()->route('admin.dashboard');
        //--- Redirect Section Ends              
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = TempCart::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends   
    }
}
