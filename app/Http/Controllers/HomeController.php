<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreQuoteRequest;
use App\Models\Quote;
use App\Models\Services;
use App\Services\CommonCrudService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public $commonService;
    public function __construct()
    {
        $this->commonService = new CommonCrudService();
    }

    public function index()
    {
        $services = $this->commonService->select(Services::class);
        return view('Customer/index', compact('services'));
    }

    public function create_quote(StoreQuoteRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pending';
        $this->commonService->create(Quote::class, $data);
        $this->sendToStaff('Pending');
        return redirect('/')->with('success', 'Quote created successfully!');
    }

    public function sendToCustomer($status_name,$customer_email,$customer_name) {
        try {
            Mail::send('emails.quote_email', [$status_name], function ($message) use ($customer_email,$status_name,$customer_name) {
                $message->to($customer_email, $customer_name)->subject('Queto has been '.$status_name);
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            });
        } catch (\Throwable $th) {
            Log::error("Getting error from mail customer");
            Log::error($th);
        }
    }

    public function sendToStaff($status_name) {
        try {
            Mail::send('emails.quote_email', [$status_name], function ($message) use ($status_name) {
                $message->to('admin_staff@gmail.com', "Admin Staff")->subject('Queto has been '.$status_name);
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            });
        } catch (\Throwable $th) {
            Log::error("Getting error from mail Staff");
            Log::error($th);
        }
    }
}
