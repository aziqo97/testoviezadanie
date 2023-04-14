<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function dashboard()
    {

    $application = Application::paginate(10);
    return view('dashboard', compact('application'));
    }
    public function store(Request $request)
    {
        $request->validate([
           'subject' => 'required|max:255',
           'message' => 'required',
            'file' => 'file|mimes:jpg,png,pdf|max:300000',
        ]);
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
            return redirect()->back();
        }
    }
}
