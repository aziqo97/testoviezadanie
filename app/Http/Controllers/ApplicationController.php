<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Mail\ApplicationCreated;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function dashboard()
    {
        $application = Application::paginate(10);
    return view('dashboard', compact('application'));
    }
    public function store(StoreApplicationRequest $request)
    {
        if ($this->checkDate()){
        redirect()->back()->with('error', 'you can create only 1 application a day');
        }


        if ($request->hasFile('file')) {
            $name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs(
                'files',
                $name,
                'public'
            );
            $application = Application::create([
                'user_id' => auth()->user()->id,
                'subject' => $request->subject,
                'message' => $request->message,
                'file_url' => $path,
            ]);
            $manager = User::first();
//            Mail::to($manager)->send(new ApplicationCreated($application));
            return redirect()->back();
        }
    }

    public function checkDate()
    {
        if (auth()->user()->applications()->latest()->first() == null){
            return false;
        }
    $last_application = auth()->user()->applications()->latest()->first();
    $last_app_date = Carbon::parse($last_application->created_at)->format('Y-m-d');
    $today = Carbon::now()->format('Y-m-d');

    if ($last_app_date === $today){
        return true;
    }
    }
}
