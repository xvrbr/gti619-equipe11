<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityOption;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrateur');
    }

    public function index()
    {
        return view('admin.security.index');
    }

    public function update(Request $request)
    {
        // Validation and update logic for security parameters will go here
        return redirect()->back()->with('success', 'Security settings updated successfully.');
    }
}
