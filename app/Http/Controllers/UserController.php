<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\datamodel;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\location_mapping;
use App\Models\product;
use App\Models\category;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(){
        return view('register');
    }

    public function register_store(Request $req)
    { 
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'number' => 'required|digits:10|unique:user,number',
            'password' => 'required|min:6',
        ],  
        [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email.',
            'email.unique' => 'This email is already exists.',
            'number.required' => 'Number is required.',
            'number.digits' => 'Mobile number must be 10 digits.',
            'number.unique' => 'This mobile number already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'number' => $req->number,
            'password' => Hash::make($req->password),
            'status' => 'Active',
        ]);

        return redirect('/login')->with('success', 'User Register Successfully');
    }

    public function login()
    {
        return view('login');
    }

    public function login_store(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        $user = User::withTrashed()
            ->where('email', $req->email)
            ->first();
        if (!$user) {
            return back()->with('error', 'Email or Password is Wrong')->withInput();
        }

        if ($user->trashed()) {
            return back()->withErrors([
                'email' => 'Your account has been deleted. Please contact the administrator.'
            ]);
        }

        // Inactive User Check
        if ($user->status == 'Inactive') {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact the administrator.'
            ]);
        }

        // Admin & Super Admin (Normal Password)
        if ($user->role == 'admin' || $user->role == 'super_admin') {
            if (Hash::check ($req->password, $user->password)) {
                Auth::guard('admin')->login($user);
                $req->session()->regenerate();
                return redirect('/admin/dashboard')
                    ->with('successe', 'Welcome Admin!');
            }
            return back()->with('error', 'Email or Password is Wrong')->withInput();
        }

        // Normal User (Hashed Password)
        if (Hash::check($req->password, $user->password)) {
            Auth::login($user);
            $req->session()->regenerate();
            DB::table('sessions')   
                ->where('user_id', $user->id)
                ->where('id', '!=', session()->getId())
                ->delete();
            return redirect('/dashboard')
                ->with('successe', 'You Are Login Successfully!');
        }

        return back()->with('error', 'Email or Password is Wrong')->withInput();
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        if ($userId) {
            DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dashboard')->with('success', 'Logout successfully!');
    }

    public function dashboard()
    {
        $totalProducts = product::count();
        $totalCategories = category::where('status', 1)->count();

        $trendingProducts = product::select(
                'product.*',
                DB::raw('SUM(order_items.quantity) as total_sales')
            )
            ->where('status', 1)
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })
            ->join('order_items', 'product.id', '=', 'order_items.product_id')
            ->groupBy(
                'product.id',
                'product.c_id',
                'product.name',
                'product.slug',
                'product.price',
                'product.image',
                'product.description',
                'product.status',
                'product.deleted_at',
                'product.created_at',
                'product.updated_at'
            )
            ->orderByDesc('total_sales')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'trendingProducts'
        ));
    }

    // Profile

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $req)
    {
        $req->validate([
            'name' => [
                'required',
                'regex:/^[A-Za-z ]+$/',
                'max:50',
            ],
            'email' => [
                'required',
                'email',
                'unique:user,email,' . Auth::id(),
            ],
            'number' => [
                'required',
                'numeric',
                'digits:10',
                'unique:user,number,' . Auth::id(),
            ],
        ],[
            'email.unique' => 'Email already exists.',
            'number.unique' => 'Phone number already exists.',
        ]);

        $user = User::findOrFail(Auth::id());
        if (
            $user->name == $req->name &&
            $user->email == $req->email &&
            $user->number == $req->number
        ) {
            return back()->with('error', 'No changes found.');
        }
        $user->update([
            'name' => $req->name,
            'email' => $req->email,
            'number' => $req->number,
        ]);

        return back()->with('success', 'Profile Updated Successfully.');
    }

    public function profile_security()
    {
        return view('security');
    }
    
    public function updatePassword(Request $req)
    {
        $req->validate([
            'current_password' => 'required',
            'password' => 'required|numeric|confirmed',
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.numeric' => 'Password must be numbers only.',
            'password.confirmed' => 'Confirm password does not match.',
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($req->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ])->withInput();
        }

        if (Hash::check($req->password, $user->password)) {
            return back()->withErrors([
                'password' => 'New password cannot be the same as the current password.'
            ])->withInput();
        }

        $user->password = Hash::make($req->password);
        $user->save();

        return redirect()->route('profile.security')
            ->with('success', 'Password Changed Successfully!');
    }

    public function about()
    {
        return view('about');
    }

public function contact()
    {
        return view('contact');
    }
}