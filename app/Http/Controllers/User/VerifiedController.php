<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\VerifiedLicense;
use Validator;
use Image;
use DB;
use Auth;

class VerifiedController extends Controller
{
    public function index() {
        if(!Auth::user()) {
            return redirect()->route('user.login');
        }
        $user_id = Auth::user()->id;
        $license_data = VerifiedLicense::where('user_id', '=', Auth::user()->id)->first();
        return view('user.verified', compact('license_data'));
    }

    public function store(Request $request) {
        $license_id = $request->license_id;
        $gs = Generalsetting::findOrFail(1);
        if(!empty($license_id)) {
            $license_model = VerifiedLicense::findOrFail($license_id);

            if($normal_license1 = $request->file('normal_license1')) {
                $name = time() . $normal_license1->getClientOriginalName();
                $input['license1'] = $name;
                $normal_license1->move('assets/images/license', $name);

                if (file_exists(public_path() . '/assets/images/license/' . $license_model->license1)) {
                    unlink(public_path() . '/assets/images/license/' . $license_model->license1);
                }
            }
            
            if($normal_license2 = $request->file('normal_license2')) {
                $name = time() . $normal_license2->getClientOriginalName();
                $input['license2'] = $name;
                $normal_license2->move('assets/images/license', $name);

                if (file_exists(public_path() . '/assets/images/license/' . $license_model->license2)) {
                    unlink(public_path() . '/assets/images/license/' . $license_model->license2);
                }
            }

            if($normal_license3 = $request->file('normal_license3')) {
                $name = time() . $normal_license3->getClientOriginalName();
                $input['license3'] = $name;
                $normal_license3->move('assets/images/license', $name);

                if (file_exists(public_path() . '/assets/images/license/' . $license_model->license3)) {
                    unlink(public_path() . '/assets/images/license/' . $license_model->license3);
                }
            }

            if($driver_license = $request->file('driver_license')) {
                $name = time() . $driver_license->getClientOriginalName();
                $input['driver_license'] = $name;
                $driver_license->move('assets/images/license', $name);

                if (file_exists(public_path() . '/assets/images/license/' . $license_model->driver_license)) {
                    unlink(public_path() . '/assets/images/license/' . $license_model->driver_license);
                }
            }

            if($bill_license = $request->file('bill_license')) {
                $name = time() . $bill_license->getClientOriginalName();
                $input['bill_license'] = $name;
                $bill_license->move('assets/images/license', $name);

                if (file_exists(public_path() . '/assets/images/license/' . $license_model->bill_license)) {
                    unlink(public_path() . '/assets/images/license/' . $license_model->bill_license);
                }
            }

            $input['user_id'] = Auth::user()->id;

            // $to = 'mr.david.hansem@outlook.com';
            // $subject = 'New License Alert';
            // $msg = Auth::user()->name." has updated a license, <a href=" . url('admin/verification/pending') . ">click here to review:</a>";
            // //Sending Email To Customer
            // if ($gs->is_smtp == 1) {
            //     $data = [
            //         'to' => $to,
            //         'subject' => $subject,
            //         'body' => $msg,
            //     ];

            //     $mailer = new GeniusMailer();
            //     $mailer->sendCustomMail($data);
            // } else {
            //     $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            //     mail($to, $subject, $msg, $headers);
            // }
            
            $license_model->update($input);
        }
        else {
            $rules = [
                'normal_license1' => 'mimes:jpeg,jpg,png|required|max:10000',
                'normal_license2' => 'mimes:jpeg,jpg,png|max:10000',
                'normal_license3' => 'mimes:jpeg,jpg,png|max:10000',
                'driver_license' => 'mimes:jpeg,jpg,png|required|max:10000',
                'bill_license' => 'mimes:jpeg,jpg,png|required|max:10000'
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $license_model = new VerifiedLicense;
        
            $normal_license1 = $request->file('normal_license1');

            $name = time() . $normal_license1->getClientOriginalName();
            $input['license1'] = $name;
            $normal_license1->move('assets/images/license', $name);

            if($normal_license2 = $request->file('normal_license2')) {
                $name = time() . $normal_license2->getClientOriginalName();
                $input['license2'] = $name;
                $normal_license2->move('assets/images/license', $name);
            }

            if($normal_license3 = $request->file('normal_license3')) {
                $name = time() . $normal_license3->getClientOriginalName();
                $input['license3'] = $name;
                $normal_license3->move('assets/images/license', $name);
            }

            $driver_license = $request->file('driver_license');

            $name = time() . $driver_license->getClientOriginalName();
            $input['driver_license'] = $name;
            $driver_license->move('assets/images/license', $name);

            $bill_license = $request->file('bill_license');

            $name = time() . $bill_license->getClientOriginalName();
            $input['bill_license'] = $name;
            $bill_license->move('assets/images/license', $name);
            
            $input['user_id'] = Auth::user()->id;

            // $to = 'mr.david.hansem@outlook.com';
            // $subject = 'New License Alert';
            // $msg = Auth::user()->name." has submitted a license, <a href=" . url('admin/verification/pending') . ">click here to review:</a>";
            // //Sending Email To Customer
            // if ($gs->is_smtp == 1) {
            //     $data = [
            //         'to' => $to,
            //         'subject' => $subject,
            //         'body' => $msg,
            //     ];

            //     $mailer = new GeniusMailer();
            //     $mailer->sendCustomMail($data);

            // } else {
            //     $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            //     mail($to, $subject, $msg, $headers);
            // }
            $license_model->fill($input)->save();
        }
    
        $msg = 'Successfully Saved your License';
        return response()->json($msg);
    }
}
