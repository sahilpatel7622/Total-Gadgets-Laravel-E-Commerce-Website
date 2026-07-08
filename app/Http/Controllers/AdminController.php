<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\datamodel;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\location_mapping;
use App\Models\category;
use App\Models\product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\MaintenanceModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function admin_dashboard()
    {
        $totalCustomers = User::where('role', 'user')->count();
        $totalCategories = Category::count();
        $totalProducts = product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $completedOrders = Order::where('status', 'Delivered')->count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $latestOrders = Order::with('user', 'payment')
            ->whereHas('user', function ($q) {
                $q->where('role', 'user')
                ->where('status', 'Active');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalCategories',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'latestOrders',
        ));
    }

    public function logout(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        Auth::guard('admin')->logout();
        if ($adminId && !Auth::guard('web')->check()) {
            DB::table('sessions')
                ->where('user_id', $adminId)
                ->delete();
        }
        
        if (!Auth::guard('web')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        
        return redirect('/')->with('success', 'Logout successfully');
    }
    
    public function Admin_users(Request $req)
    {
        $record = User::query();
        if ($req->filled('search')) {
            $record->where('name', 'LIKE', '%' . $req->search . '%')
                ->orWhere('email', 'LIKE', '%' . $req->search . '%')
                ->orWhere('number', 'LIKE', '%' . $req->search . '%');

        }

        $record = User::where('role', 'user')
            ->latest()
            ->get();
        return view('admin.users', compact('record'));
    } 

    public function user_delete($id)
    {
        $record = User::find($id);
        $record->delete();

        return redirect('admin/users')->with('success', 'User Deleted Successfully');
    }
    
    public function Admin_data()
    {
        $record = datamodel::with('locationMapping.country', 'locationMapping.state', 'locationMapping.city')
                            ->get();
        return view('admin.data',compact('record'));
    }
    
    public function data_delete($id)
    {
        $record = datamodel::findOrFail($id);

        if ($record->image && file_exists(public_path('uploads/' . $record->image))) {
            unlink(public_path('uploads/' . $record->image));
        }

        location_mapping::where('data_id', $id)->delete();
        $record->delete();
        return redirect('admin/data')->with('success', 'Record Deleted Successfully');
    }

    public function userStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 'Active'
            ? 'Inactive'
            : 'Active';

        $user->save();
        return back()->with('success', 'User status updated successfully.');
    }


    public function changeStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->status == 'Active') {
            $user->status = 'Inactive';
        } else {
            $user->status = 'Active';
        }

        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully');
    }

    // Category
    public function Admin_category(Request $req)
    {
        $record = category::query();

        if ($req->filled('search')) {

            $record->where('id', 'LIKE', '%' . $req->search . '%')
                ->orWhere('name', 'LIKE', '%' . $req->search . '%')
                ->orWhere(function ($q) use ($req) {

                        if (strtolower($req->search) == 'active') {
                            $q->where('status', 1);
                        }

                        if (strtolower($req->search) == 'inactive') {
                            $q->where('status', 0);
                        }

                });
        }

        $record = $record->latest()->get();

        return view('admin.category.index', compact('record'));
    }

    public function Add_category(){
        return view('admin.category.add_category');
    }

    public function Store_category(Request $req){
        $req->validate([
            'name' => 'required|unique:category,name',
            'slug' => 'required|unique:category,slug',
            'status' => 'required',
        ]);

        category::create([
            'name' => $req->name,   
            'slug' => $req->slug,
            'status' => $req->status,
        ]);
        return redirect('/admin/category')->with('success', 'Category Add Successfully!');
    }

    public function Delete_category($id)
    {
        $record = category::find($id);
        $record->delete();

        return redirect('admin/category')->with('success', 'Category Deleted Successfully');
    }

    public function edit_category($id){
        $record = category::findOrFail($id);
        return view('admin/category/edit_category',compact('record'));
    }

    public function update_category(Request $req, $id){
        $req->validate(
        [
        'name' => [
            'required',
            Rule::unique('category', 'name')->ignore($id),
        ],
        'slug' => [
            'required',
            Rule::unique('category', 'slug')->ignore($id),
        ],]);

        $record = category::findOrFail($id);

        $record->name = $req->name;
        $record->slug = $req->slug;
        if (!$record->isDirty()) {
            return redirect('/admin/category')->with('info', 'No changes found.');
        }
        $record->save();
        return redirect('admin/category')->with('success', 'Category Update Successfully');
    }

    public function changeStatus_category($id)
    {
        $category = category::findOrFail($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return redirect('/admin/category')->with('success', 'Category status updated successfully');
    }

    
    // Products
    public function Admin_product(Request $req)
    {
        $product = product::with('category');
        if ($req->filled('search')) {
            $product->where('name', 'LIKE', '%' . $req->search . '%')
                    ->orWhere('price', 'LIKE', '%' . $req->search . '%')
                    ->orWhereHas('category', function ($q) use ($req) {
                        $q->where('name', 'LIKE', '%'.$req->search.'%');
                    });
        }
        $product = $product->latest()->get();
        return view('admin.product.index', compact('product'));
    }

    public function Add_product(){
        $record = category::where('status',1)->get();
        return view('admin.product.add_product',compact('record'));
    }


    public function Store_product(Request $req)
    {
        $req->validate([
            'c_id' => 'required',
            'name' => 'required|unique:product,name',
            'slug' => 'required|unique:product,slug',
            'price' => 'required|numeric',
            'image' => 'required|nullable|image|mimes:jpg,jpeg,png,webp',
            'description' => 'required|nullable',
        ]);

        $imageName = null;

        if ($req->hasFile('image')) {
            $imageName = time().'.'.$req->image->extension();
            $req->image->move(public_path('product'), $imageName);
        }

        product::create([
            'c_id' => $req->c_id,
            'name' => $req->name,
            'slug' => $req->slug,
            'price' => $req->price,
            'image' => $imageName,
            'description' => $req->description,
        ]);

        return redirect('/admin/product')
                ->with('success', 'Product Added Successfully!');
    }

    public function Delete_product($id)
    {
        $record = product::find($id);
        if ($record->image && file_exists(public_path('product/' . $record->image))) {
            unlink(public_path('product/' . $record->image));
        }

        $record->delete();
        return redirect('admin/product')->with('success', 'Product Deleted Successfully');
    }

    public function edit_product($id){
        $record = product::findOrFail($id);
        $category = category::where('status',1)->get();
        return view('admin/product/edit_product',compact('record','category'));
    }

    public function Update_product(Request $req, $id){

        $req->validate([
        'c_id' => 'required',
        'name' => [
            'required',
            Rule::unique('product', 'name')->ignore($id),
        ],
        'slug' => [
            'required',
            Rule::unique('product', 'slug')->ignore($id),
        ],
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'description' => 'nullable',
    ]);

    $product = product::findOrFail($id);

    $product->c_id = $req->c_id;
    $product->name = $req->name;
    $product->slug = $req->slug;
    $product->price = $req->price;
    $product->description = $req->description;

    if ($req->hasFile('image')) {

        if ($product->image && file_exists(public_path('product/'.$product->image))) {
            unlink(public_path('product/'.$product->image));
        }

        $imageName = time().'.'.$req->image->extension();
        $req->image->move(public_path('product'), $imageName);
        $product->image = $imageName;
    }

    if (!$product->isDirty()) {
        return redirect('/admin/product')->with('info', 'No changes found.');
    }
    $product->save();
    return redirect('/admin/product')
            ->with('success', 'Product Updated Successfully!');
    }

    // Order
    public function Admin_orders()
    {
        $orders = Order::with(['user', 'payment'])
            ->latest()
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    public function Admin_order_view($id)
    {
        $order = Order::with('user', 'items.product')
            ->findOrFail($id);

        return view('admin.order.order_view', compact('order'));
    }

    public function Admin_order_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }

    // Payment
    public function Admin_payments(Request $request)
    {
        $payments = Payment::with(['order', 'user'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('payment_method', 'like', '%' . $request->search . '%')
                    ->orWhere('payment_status', 'like', '%' . $request->search . '%')
                    ->orWhereHas('order', function ($q) use ($request) {
                        $q->where('order_number', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->latest()
            ->get();

        return view('admin.payment.index', compact('payments'));
    }

    public function Admin_payment_view($id)
    {
        $payment = Payment::with(['order.items.product', 'user'])
            ->findOrFail($id);

        return view('admin.payment.view', compact('payment'));
    }

    public function Admin_payment_status(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:Pending,Paid,Failed,Refunded',
        ]);
        $payment = Payment::findOrFail($id);
        $payment->payment_status = $request->payment_status;
        $payment->save();

        return back()->with('success', 'Payment status updated successfully.');
    }


    // Profile
    public function admin_profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $authAdmin = Auth::guard('admin')->user();
        $admin = User::findOrFail($authAdmin->id);
        $request->validate([
            'name'  => 'required',
            'email' => ['required','email',
                Rule::unique('user', 'email')->ignore($admin->id),
            ],
        ]);

        if ($admin->name == $request->name && $admin->email == $request->email) {
            return back()->with('info', 'No changes found.');
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function passwordUpdate(Request $request)
    {
        $admin = User::findOrFail(Auth::guard('admin')->id());
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ],[
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 6 characters.',
            'password.confirmed' => 'Confirm password does not match.',
        ]);

        // Check if current password is correct (plain text or hashed)
        if ($request->current_password != $admin->password && !Hash::check($request->current_password, $admin->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Current password is incorrect.'
                ])
                ->withInput();
        }

        if ($request->current_password == $request->password) {
            return back()
                ->withErrors([
                    'password' => 'New password must be different from the current password.'
                ])
                ->withInput();
        }
        
        $admin->password = $request->password;
        $admin->save();
        return back()->with('success', 'Password updated successfully.');
    }


    // Maintenance Mode
    public function maintenance()
    {
        $setting = MaintenanceModel::firstOrCreate(
            ['id' => 1],
            ['maintenance_mode' => 1]
        );

        $setting->maintenance_mode = !$setting->maintenance_mode;
        $setting->save();

        return back()->with('success', 'Maintenance Mode Updated Successfully.');
    }

}
