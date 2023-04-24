<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\Campaign;
use App\Models\CampaignOption;
use Auth;
use Session;

use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function text_campaign() {
        return view('admin.campaign.campaign');
    }

    //*** JSON Request
    public function campaign_datatables()
    {
        $datas = Campaign::where('approved', 0);
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('name', function (Campaign $data) {
                $name = $data->get_user->name;
                return $name;
            })
            ->addColumn('email', function (Campaign $data) {
                $email = $data->get_user->email;
                return $email;
            })
            ->editColumn('number_texts', function (Campaign $data) {
                $number_texts = $data->get_option->number_texts;
                return $number_texts;
            })
            ->addColumn('price', function (Campaign $data) {
                $price = $data->get_option->price;
                return $price;
            })
            ->editColumn('message', function (Campaign $data) {
                $message = $data->message;
                return $message;
            })
            ->addColumn('link', function (Campaign $data) {
                $link = $data->link;
                return $link;
            })
            ->addColumn('action', function (Campaign $data) {
                return '<div class="godropdown">
                    <button class="go-dropdown-toggle"> Review<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="javascript:;" class="approved_link" data-id="'.$data->id.'" data-flag="'.$data->approved.'"><i class="fas fa-check"></i> Approve</a>
                        <a href="javascript:;" class="deny_link" data-id="'.$data->id.'" data-flag="'.$data->approved.'"><i class="fas fa-trash-alt"></i> Deny</a>
                    </div>
                </div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function campaign_approve(Request $request) {
        $id = $request->approve_id;
        
        $data = Campaign::findOrFail($id);
        $data->approved = 1;
        $data->update();

        $msg = 'Approved Successfully.';
        Session::put('success', $msg);

        return redirect()->route('admin-campaign');
    }

    public function campaign_deny(Request $request) {
        $id = $request->deny_id;
        $message = $request->message;

        $data = Campaign::findOrFail($id);
        $user_id = $data->vendor_id;
        $user = User::find($user_id);
        
        // $to = $user->email;
        // $subject = 'Deny Text Campaign';
        // $gs = Generalsetting::findOrFail(1);
        // $msg = "From: " . $gs->from_name . "<" . $gs->from_email . ">" . "<br>Message: " . $request->message;

        // if ($gs->is_smtp == 1) {
        //     $datas = [
        //         'to' => $to,
        //         'subject' => $subject,
        //         'body' => $msg,
        //     ];
        //     $mailer = new GeniusMailer();
        //     $mailer->sendCustomMail($datas);
        // } else {
        //     $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
        //     mail($to, $subject, $msg, $headers);
        // }

        $data->approved = -1;
        $data->update();
        
        $msg = 'Denied Successfully.';
        Session::put('success', $msg);

        return redirect()->route('admin-campaign');
    }

    public function text_campaign_history() {
        return view('admin.campaign.campaign_history');
    }

    public function campaign_history_datatables()
    {
        $datas = Campaign::where('approved', -1)->orWhere('approved', 1)->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('name', function (Campaign $data) {
                $name = $data->get_user->name;
                return $name;
            })
            ->addColumn('email', function (Campaign $data) {
                $email = $data->get_user->email;
                return $email;
            })
            ->editColumn('number_texts', function (Campaign $data) {
                $number_texts = $data->get_option->number_texts;
                return $number_texts;
            })
            ->addColumn('price', function (Campaign $data) {
                $price = $data->get_option->price;
                return $price;
            })
            ->editColumn('message', function (Campaign $data) {
                $message = $data->message;
                return $message;
            })
            ->addColumn('link', function (Campaign $data) {
                $link = $data->link;
                return $link;
            })
            ->addColumn('approved', function (Campaign $data) {
                $approved = $data->approved;

                if($approved == 1) {
                    $cls = "drop-success";
                    $txt = "Approved";
                }
                else {
                    $cls = "drop-danger";
                    $txt = "Denied";
                }

                return '<div class="action-list"><span class="nice-select process '.$cls.'">'.$txt.'</span></div>';
            })
            ->rawColumns(['approved'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function text_campaign_detail() {
        return view('admin.campaign.campaign_detail');
    }

    public function campaign_detail_datatables() {
        $datas = CampaignOption::orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('number_texts', function (CampaignOption $data) {
                $number_texts = $data->number_texts;
                return $number_texts;
            })
            ->editColumn('price', function (CampaignOption $data) {
                $price = $data->price;
                return $price;
            })
            ->addColumn('action', function(CampaignOption $data) {
                return '<div class="action-list">
                    <a href="' . route('admin-campaign-detail-edit', $data->id) . '">
                        <i class="fas fa-edit"></i>Edit
                    </a>
                    <a href="javascript:;" data-href="' . route('admin-campaign-detail-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['approved', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function edit_detail(Request $request, $id)
    {
        $data = CampaignOption::findOrFail($id);
        return view('admin.campaign.campaign_detail_edit', compact('data'));
    }

    public function update_detail(Request $request, $id)
    {
        //--- Validation Section
        $rules = array(
            'number_texts' => 'required',
            'price' => 'required',
        );
        $customs = array(
            'number_texts.required' => 'Number of Text field is required.',
            'price.required' => 'Price field is required.',
        );

        $validator = Validator::make(  $request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section

        $data = CampaignOption::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Campaign Option Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends            
    }

    public function destroy_detail($id)
    {
        $data = CampaignOption::findOrFail($id);
        $data->delete();

        $msg = 'Campaign Option Deleted Successfully.';
        
        return response()->json($msg);
    }
}