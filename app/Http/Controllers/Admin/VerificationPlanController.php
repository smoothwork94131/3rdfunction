<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\VerificationPlan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\VerifiedLicense;
use App\Models\Generalsetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\GeniusMailer;
use Validator;
use Session;

class VerificationPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = VerificationPlan::orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('price', function (VerificationPlan $data) {
                $price = round($data->price, 2);
                return $price;
            })
            ->editColumn('type', function (VerificationPlan $data) {
                $type = $data->type == 0 ? "Buyer" : "Seller";
                return $type;
            })
            ->addColumn('action', function (VerificationPlan $data) {
                return '<div class="action-list"><a data-href="' . route('admin-verification-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-verification-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.verificationplan.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.verificationplan.create');
    }

    //*** POST Request
    public function store(Request $request)
    {

        //--- Logic Section
        $data = new VerificationPlan();
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
        $data = VerificationPlan::findOrFail($id);
        return view('admin.verificationplan.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = VerificationPlan::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = VerificationPlan::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends     
    }

    public function pending() {
        return view('admin.verificationplan.pending');
    }

    public function pending_datatables()
    {
        $datas = VerifiedLicense::select("*")
                ->join('users', 'users.id', '=', 'verified_license.user_id')
                ->where('is_verified', '=', 0)
                ->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('name', function (VerifiedLicense $data) {
                $name = $data->get_User->name;
                return $name;
            })
            ->addColumn('email', function (VerifiedLicense $data) {
                $email = $data->email;
                return $email;
            })
            ->addColumn('license1', function (VerifiedLicense $data) {
                $license1 = $data->license1;
                if($license1 == '') {
                    return '';
                }
                else {
                    $license1_image = asset('assets/images/license/'.$license1);
                    return '<img src="'. $license1_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('license2', function (VerifiedLicense $data) {
                $license2 = $data->license2;
                if($license2 == '') {
                    return '';
                }
                else {
                    $license2_image = asset('assets/images/license/'.$license2);
                    return '<img src="'. $license2_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('license3', function (VerifiedLicense $data) {
                $license3 = $data->license3;
                if($license3 == '') {
                    return '';
                }
                else {
                    $license3_image = asset('assets/images/license/'.$license3);
                    return '<img src="'. $license3_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('driver_license', function (VerifiedLicense $data) {
                $driver_license = $data->driver_license;
                if($driver_license == '') {
                    return '';
                }
                else {
                    $driver_license_image = asset('assets/images/license/'.$driver_license);
                    return '<img src="'. $driver_license_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('bill_license', function (VerifiedLicense $data) {
                $bill_license = $data->bill_license;
                if($bill_license == '') {
                    return '';
                }
                else {
                    $bill_license_image = asset('assets/images/license/'.$bill_license);
                    return '<img src="'. $bill_license_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('action', function (VerifiedLicense $data) {
                return '<div class="godropdown">
                        <button class="go-dropdown-toggle">Actions<i class="fas fa-chevron-down"></i></button>
                        <div class="action-list">
                            <a href="javascript:;" data-id="' . $data->user_id . '" class="update-approve"><i class="fas fa-check"></i> Approve</a>
                            <a href="' . route('admin-verification-message', ['id1' => $data->user_id]) . '"> <i class="fas fa-envelope"></i> Message</a>
                        </div>
                    </div>';
            })
            ->rawColumns(['action', 'license1', 'license2', 'license3', 'driver_license', 'bill_license'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function approve() {
        return view('admin.verificationplan.approve');
    }
    
    public function approve_datatables()
    {
        $datas = VerifiedLicense::select("*")
                ->join('users', 'users.id', '=', 'verified_license.user_id')
                ->where('is_verified', '=', 1)
                ->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('name', function (VerifiedLicense $data) {
                $name = $data->get_User->name;
                return $name;
            })
            ->addColumn('email', function (VerifiedLicense $data) {
                $email = $data->email;
                return $email;
            })
            ->addColumn('license1', function (VerifiedLicense $data) {
                $license1 = $data->license1;
                if($license1 == '') {
                    return '';
                }
                else {
                    $license1_image = asset('assets/images/license/'.$license1);
                    return '<img src="'. $license1_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('license2', function (VerifiedLicense $data) {
                $license2 = $data->license2;
                if($license2 == '') {
                    return '';
                }
                else {
                    $license2_image = asset('assets/images/license/'.$license2);
                    return '<img src="'. $license2_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('license3', function (VerifiedLicense $data) {
                $license3 = $data->license3;
                if($license3 == '') {
                    return '';
                }
                else {
                    $license3_image = asset('assets/images/license/'.$license3);
                    return '<img src="'. $license3_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('driver_license', function (VerifiedLicense $data) {
                $driver_license = $data->driver_license;
                if($driver_license == '') {
                    return '';
                }
                else {
                    $driver_license_image = asset('assets/images/license/'.$driver_license);
                    return '<img src="'. $driver_license_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('bill_license', function (VerifiedLicense $data) {
                $bill_license = $data->bill_license;
                if($bill_license == '') {
                    return '';
                }
                else {
                    $bill_license_image = asset('assets/images/license/'.$bill_license);
                    return '<img src="'. $bill_license_image .'" widht="50px" align="center" class="img-round" />';
                }
            })
            ->addColumn('action', function (VerifiedLicense $data) {
                return '<div class="godropdown">
                    <button class="go-dropdown-toggle">Actions<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-verification-update-pending', ['id' => $data->user_id]) . '"> <i class="fas fa-info"></i> Pending</a>
                        <a href="' . route('admin-verification-message', ['id1' => $data->user_id]) . '"> <i class="fas fa-envelope"></i> Message</a>
                    </div>
                </div>';
            })
            ->rawColumns(['action', 'license1', 'license2', 'license3', 'driver_license', 'bill_license'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function update_approve(Request $request) {
        $data = User::findOrFail($request->user_id);
        $verified = VerifiedLicense::where('user_id', $request->user_id)->first();
        $verified->expires = date("Y-m-d", strtotime($request->expire_date));

        $verified->save();
        
        $data->is_verified = 1;
        $data->update();

        $msg = 'Approved Successfully.';
        Session::put('success', $msg);

        return redirect()->route('admin-verification-pending');
    }

    public function update_pending($id) {
        $data = User::findOrFail($id);
        $verified = VerifiedLicense::where('user_id', $id)->first()->delete();

        $data->is_verified = 0;
        $data->update();

        $msg = 'Pending Successfully.';
        Session::put('success', $msg);

        return redirect()->back();
    }

    public function message($user_id) {
        return view('admin.verificationplan.message', array(
            'user_id' => $user_id
        ));
    }

    public function send_message(Request $request) {
        $user_id = $request->user_id;
        $subject = $request->subject;
        $message = $request->message;

        $gs = Generalsetting::findOrFail(1);

        $user_data = User::findOrFail($user_id);

        $to = $user_data->email;
        
        //Sending Email To Customer

        try{
            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $message,
                ];
    
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $message, $headers);
            }

            $msg = 'Message be sent Successfully.';
            return response()->json($msg);
        }
        catch(exception $e) {
            $msg = $e->getMessage();
            return response()->json($msg);
        }
    }
}
