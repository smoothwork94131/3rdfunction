<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\Category;
use App\Models\Withdraw;
use App\Models\Currency;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = User::orderBy('id')
                ->where('is_vendor', '=', 0)
                ->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('action', function (User $data) {
                $class = $data->ban == 0 ? 'drop-success' : 'drop-danger';
                $s = $data->ban == 1 ? 'selected' : '';
                $ns = $data->ban == 0 ? 'selected' : '';
                $ban = '<select class="process select droplinks ' . $class . '">' .
                    '<option data-val="0" value="' . route('admin-user-ban', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Block</option>' .
                    '<option data-val="1" value="' . route('admin-user-ban', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>UnBlock</option></select>';

                $class1 = $data->cod == 1 ? 'drop-success' : 'drop-danger';
                $s1 = $data->cod == 0 ? 'selected' : '';
                $ns1 = $data->cod == 1 ? 'selected' : '';

                $cod = '<select class="process select droplinks ' . $class1 . '">' .
                    '<option data-val="0" value="' . route('admin-user-cod', ['id1' => $data->id, 'id2' => 0]) . '" ' . $s1 . '>UnCod</option>' .
                    '<option data-val="1" value="' . route('admin-user-cod', ['id1' => $data->id, 'id2' => 1]) . '" ' . $ns1 . '>Cod</option></select>';

                return '<div class="action-list"><a href="' . route('admin-user-show', $data->id) . '" > <i class="fas fa-eye"></i> Details</a><a data-href="' . route('admin-user-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" class="send" data-email="' . $data->email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a>' . $ban . $cod. '<a href="javascript:;" data-href="' . route('admin-user-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.user.index');
    }

    //*** GET Request
    public function image()
    {
        return view('admin.generalsetting.user_image');
    }

    //*** GET Request
    public function show($id)
    {
        if (!User::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $data = User::findOrFail($id);
        return view('admin.user.show', compact('data'));
    }

    //*** GET Request
    public function ban($id1, $id2)
    {
        $user = User::findOrFail($id1);
        $user->ban = $id2;
        $user->update();

    }

    public function cod($id1, $id2)
    {
        $user = User::findOrFail($id1);
        $user->cod = $id2;
        $user->update();

    }

    //*** GET Request
    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'photo' => 'mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make(  $request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $user = User::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('assets/images/users', $name);
            if ($user->photo != null) {
                if (file_exists(public_path() . '/assets/images/users/' . $user->photo)) {
                    unlink(public_path() . '/assets/images/users/' . $user->photo);
                }
            }
            $data['photo'] = $name;
        }
        $user->update($data);
        $msg = 'Customer Information Updated Successfully.';
        return response()->json($msg);
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);


        if ($user->reports->count() > 0) {
            foreach ($user->reports as $gal) {
                $gal->delete();
            }
        }


        if ($user->shippings->count() > 0) {
            foreach ($user->shippings as $gal) {
                $gal->delete();
            }
        }


        if ($user->packages->count() > 0) {
            foreach ($user->packages as $gal) {
                $gal->delete();
            }
        }


        if ($user->ratings->count() > 0) {
            foreach ($user->ratings as $gal) {
                $gal->delete();
            }
        }

        if ($user->notifications->count() > 0) {
            foreach ($user->notifications as $gal) {
                $gal->delete();
            }
        }

        if ($user->wishlists->count() > 0) {
            foreach ($user->wishlists as $gal) {
                $gal->delete();
            }
        }

        if ($user->withdraws->count() > 0) {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if ($user->socialProviders->count() > 0) {
            foreach ($user->socialProviders as $gal) {
                $gal->delete();
            }
        }

        if ($user->conversations->count() > 0) {
            foreach ($user->conversations as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }
        if ($user->comments->count() > 0) {
            foreach ($user->comments as $gal) {
                if ($gal->replies->count() > 0) {
                    foreach ($gal->replies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($user->replies->count() > 0) {
            foreach ($user->replies as $gal) {
                if ($gal->subreplies->count() > 0) {
                    foreach ($gal->subreplies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }


        if ($user->favorites->count() > 0) {
            foreach ($user->favorites as $gal) {
                $gal->delete();
            }
        }


        if ($user->subscribes->count() > 0) {
            foreach ($user->subscribes as $gal) {
                $gal->delete();
            }
        }

        if ($user->services->count() > 0) {
            foreach ($user->services as $gal) {
                if (file_exists(public_path() . '/assets/images/services/' . $gal->photo)) {
                    unlink(public_path() . '/assets/images/services/' . $gal->photo);
                }
                $gal->delete();
            }
        }


        if ($user->withdraws->count() > 0) {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }


        if ($user->products->count() > 0) {

// PRODUCT

            foreach ($user->products as $prod) {
                if ($prod->galleries->count() > 0) {
                    foreach ($prod->galleries as $gal) {
                        if (file_exists(public_path() . '/assets/images/galleries/' . $gal->photo)) {
                            unlink(public_path() . '/assets/images/galleries/' . $gal->photo);
                        }
                        $gal->delete();
                    }

                }
                if ($prod->ratings->count() > 0) {
                    foreach ($prod->ratings as $gal) {
                        $gal->delete();
                    }
                }
                if ($prod->wishlists->count() > 0) {
                    foreach ($prod->wishlists as $gal) {
                        $gal->delete();
                    }
                }

                if ($prod->clicks->count() > 0) {
                    foreach ($prod->clicks as $gal) {
                        $gal->delete();
                    }
                }

                if ($prod->comments->count() > 0) {
                    foreach ($prod->comments as $gal) {
                        if ($gal->replies->count() > 0) {
                            foreach ($gal->replies as $key) {
                                $key->delete();
                            }
                        }
                        $gal->delete();
                    }
                }

                if (file_exists(public_path() . '/assets/images/products/' . $prod->photo)) {
                    unlink(public_path() . '/assets/images/products/' . $prod->photo);
                }

                $prod->delete();
            }


// PRODUCT ENDS

        }
// OTHER SECTION 


        if ($user->senders->count() > 0) {
            foreach ($user->senders as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }


        if ($user->recievers->count() > 0) {
            foreach ($user->recievers as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }


        if ($user->conversations->count() > 0) {
            foreach ($user->conversations as $gal) {
                if ($gal->messages->count() > 0) {
                    foreach ($gal->messages as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }


        if ($user->vendororders->count() > 0) {
            foreach ($user->vendororders as $gal) {
                $gal->delete();
            }
        }

        if ($user->notivications->count() > 0) {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }


// OTHER SECTION ENDS


        //If Photo Doesn't Exist
        if ($user->photo == null) {
            $user->delete();
            //--- Redirect Section
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path() . '/assets/images/users/' . $user->photo)) {
            unlink(public_path() . '/assets/images/users/' . $user->photo);
        }
        $user->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** JSON Request
    public function withdrawdatatables()
    {
        $datas = Withdraw::where('type', '=', 'user')->orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('email', function (Withdraw $data) {
                $email = $data->user->email;
                return $email;
            })
            ->addColumn('phone', function (Withdraw $data) {
                $phone = $data->user->phone;
                return $phone;
            })
            ->editColumn('status', function (Withdraw $data) {
                $status = ucfirst($data->status);
                return $status;
            })
            ->editColumn('amount', function (Withdraw $data) {
                $sign = Currency::where('is_default', '=', 1)->first();
                $amount = $sign->sign . round($data->amount * $sign->value, 2);
                return $amount;
            })
            ->addColumn('action', function (Withdraw $data) {
                $action = '<div class="action-list"><a data-href="' . route('admin-withdraw-show', $data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> Details</a>';
                if ($data->status == "pending") {
                    $action .= '<a data-href="' . route('admin-withdraw-accept', $data->id) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="fas fa-check"></i> Accept</a><a data-href="' . route('admin-withdraw-reject', $data->id) . '" data-toggle="modal" data-target="#confirm-delete1"> <i class="fas fa-trash-alt"></i> Reject</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['name', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function withdraws()
    {
        return view('admin.user.withdraws');
    }

    //*** GET Request
    public function withdrawdetails($id)
    {
        $sign = Currency::where('is_default', '=', 1)->first();
        $withdraw = Withdraw::findOrFail($id);
        return view('admin.user.withdraw-details', compact('withdraw', 'sign'));
    }

    //*** GET Request
    public function accept($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = "completed";
        $withdraw->update($data);
        //--- Redirect Section
        $msg = 'Withdraw Accepted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $account = User::findOrFail($withdraw->user->id);
        $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
        $account->update();
        $data['status'] = "rejected";
        $withdraw->update($data);
        //--- Redirect Section
        $msg = 'Withdraw Rejected Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    //*** GET Request
    public function track($flag)
    {
        return view('admin.user.track', compact('flag'));
    }

    //*** JSON Request
    public function trackdatatables(Request $request)
    {
        $flag = $request->flag;

        if($flag == 'all' || !$flag) {
            $datas = ProductClick::orderBy('id')->get();
        }
        else {
            $datas = ProductClick::orderBy('id')->where('action', $flag)->get();
        }
    
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('user', function (ProductClick $data) {
                if($data->user_id == 0) {
                    return 'Unknown';
                }
                else {
                    $user = $data->get_user['name'];
                    return $user;
                }
            })
            ->addColumn('category', function (ProductClick $data) {
                $category = $data->get_category['name'];
                return $category;
            })
            ->addColumn('product', function (ProductClick $data) {
                if($data->product_id == 0) {
                    return '---';
                }
                else {
                    $product = mb_strlen(strip_tags($data->get_product['name']), 'utf-8') > 50 ? mb_substr(strip_tags($data->get_product['name']), 0, 50, 'utf-8') . '...' : strip_tags($data->get_product->name);
                    return $product;
                }
            })
            ->addColumn('search_term', function (ProductClick $data) {
                $search_term = mb_strlen(strip_tags($data->search_term), 'utf-8') > 50 ? mb_substr(strip_tags($data->search_term), 0, 50, 'utf-8') . '...' : strip_tags($data->search_term);
                if($search_term == '') {
                    return '';
                }
                else {
                    return $search_term;
                }
            })
            ->addColumn('Date', function (ProductClick $data) {
                $date = $data->date;
                return $date;
            })
            ->addColumn('action', function (ProductClick $data) {
                $action = $data->action;

                if($action == 'view') {
                    $cls = "drop-success";
                    $txt = "Cart View";
                }
                else if($action == 'quick_view') {
                    $cls = "drop-primary";
                    $txt = "Quick View";
                }
                else if($action == 'compare') {
                    $cls = "drop-info";
                    $txt = "compare";
                }
                else if($action == 'search') {
                    $cls = "drop-warning";
                    $txt = "Search Term";
                }
                else {
                    $cls = "drop-danger";
                    $txt = "Category Menu";
                }

                return '<div class="action-list"><span class="nice-select process '.$cls.'">'.$txt.'</span></div>';
            })
            ->rawColumns(['user', 'category', 'product', 'search', 'date', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

}