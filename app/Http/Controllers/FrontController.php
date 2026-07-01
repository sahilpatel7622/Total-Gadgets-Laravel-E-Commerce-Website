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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontController extends Controller
{
    public function register(){
        return view('register');
    }

    public function register_store(Request $req)
    { 
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'number' => 'required|min:10|max:10|unique:user,number',
            'password' => 'required|min:6',
        ],  
        [
            'email.unique' => 'This email is already exit.',
            'mobile.unique' => 'This mobile number is already exit.',
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
        $credentials = $req->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status == 'Inactive') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.'
                ]);
            }
            $req->session()->regenerate();
            return redirect('/dashboard')->with('successe', 'You Are Login Successfully!');
        }
        else {
            return redirect()->back()->withErrors(['email' => 'Email or Password is Wrong'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dashboard')->with('success', 'Logout successfully');
    }

    public function dashboard()
    {
        $totalProducts = product::count();
        $totalCategories = category::where('status', 1)->count();
        $latestProducts = product::with('category')->latest()->take(4)->get();

        return view('dashboard', compact('totalProducts', 'totalCategories', 'latestProducts'));
    }


    public function insert_i(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:data,email',
            'number' => 'required|min:10|max:10|unique:data,number',
            'address' => 'required',
            'gender' => 'required',
            'image' => 'required|image',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);

        $imageName = time().'.'.$req->image->extension();
        $req->image->move(public_path('uploads'), $imageName);

        $member = datamodel::create([
            'user_id' => Auth::id(),
            'name' => $req->name,
            'email' => $req->email,
            'number' => $req->number,
            'address' => $req->address,
            'gender' => $req->gender,
            'image' => $imageName,
        ]);

        location_mapping::create([
            'user_id' => Auth::id(),
            'data_id' => $member->id,
            'countries_id' => $req->country_id,
            'states_id' => $req->state_id,
            'cities_id' => $req->city_id,
        ]);

        return redirect()->route('view')->with('success', 'Data Inserted Successfully');
    }

    public function view()
    {
        $record = datamodel::where('user_id', Auth::id())->get();
        return view('crud.view', compact('record'));
    }

    public function delete($id)
    {
        $record = datamodel::findOrFail($id);

        location_mapping::where('data_id', $id)->delete();

        if ($record->image && file_exists(public_path('uploads/'.$record->image))) {
            unlink(public_path('uploads/'.$record->image));
        }

        $record->delete();

        return redirect()->route('view')->with('success', 'Record Deleted Successfully');
    }

    public function edit($id)
    {
        $record = datamodel::findOrFail($id);
        $countries = Country::all();

        $mapping = location_mapping::where('data_id', $id)->first();

        $states = $mapping
            ? State::where('country_id', $mapping->countries_id)->get()
            : collect();

        $cities = $mapping
            ? City::where('state_id', $mapping->states_id)->get()
            : collect();

        return view('crud.update', compact('record', 'countries', 'mapping', 'states', 'cities'));
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email',
            'number' => 'required|min:10|max:10',
            'address' => 'required',
            'gender' => 'required',
            'image' => 'nullable|image',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);

        $record = datamodel::findOrFail($id);

        $record->name = $req->name;
        $record->email = $req->email;
        $record->number = $req->number;
        $record->address = $req->address;
        $record->gender = $req->gender;

        if ($req->hasFile('image')) {
            if ($record->image && file_exists(public_path('uploads/'.$record->image))) {
                unlink(public_path('uploads/'.$record->image));
            }

            $imageName = time().'.'.$req->image->extension();
            $req->image->move(public_path('uploads'), $imageName);
            $record->image = $imageName;
        }

        $record->save();

        location_mapping::updateOrCreate(
            ['data_id' => $id],
            [
                'user_id' => Auth::id(),
                'countries_id' => $req->country_id,
                'states_id' => $req->state_id,
                'cities_id' => $req->city_id,
            ]
        );

        return redirect()->route('view')->with('success', 'Record Updated Successfully');
    }

    public function index()
    {
        $countries = Country::all();

        $states = old('country_id')
            ? State::where('country_id', old('country_id'))->get()
            : collect();

        $cities = old('state_id')
            ? City::where('state_id', old('state_id'))->get()
            : collect();

        return view('crud.index', compact('countries', 'states', 'cities'));
    }

    public function getStates($country_id)
    {
        return State::where('country_id', $country_id)->get();
    }

    public function getCities($state_id)
    {
        return City::where('state_id', $state_id)->get();
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
            'password' => 'required|numeric|digits:6|confirmed',
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

    public function about(){
        return view('about');
    }
}