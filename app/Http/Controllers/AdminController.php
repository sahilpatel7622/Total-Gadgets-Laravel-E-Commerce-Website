<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin_usermodel;
use App\Models\datamodel;
use App\Models\User;
use App\Models\location_mapping;
use App\Models\category;
use App\Models\product;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\Payment;


class AdminController extends Controller
{
    public function admin_login()
    {
        return view('admin.index');
    }

    public function admin_login_store(Request $request)
    {
        $admin = Admin_usermodel::where('email', $request->email)->first();

        if ($admin && $admin->password === $request->password) {

            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_id', $admin->id);
            $request->session()->put('admin_name', $admin->name);
            

            return redirect('/admin/dashboard')
                ->with('successe', 'Admin Login Successfully!');
        }

        return redirect()->back()
        ->withInput()
        ->with('error', 'Invalid email or password');
    }

    public function admin_dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_logged_in',
            'admin_name',
            'admin_id',
        ]);

        return redirect('/admin')->with('success', 'Logout successfully');
    }
    
    public function Admin_users(Request $req)
    {
        $record = User::query();

        if ($req->filled('search')) {

            $record->where('name', 'LIKE', '%' . $req->search . '%')
                ->orWhere('email', 'LIKE', '%' . $req->search . '%')
                ->orWhere('number', 'LIKE', '%' . $req->search . '%');

        }

        $record = $record->latest()->get();

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
        ],
            // 'status' => 'required',
        ]);

        $record = category::findOrFail($id);

        $record->name = $req->name;
        $record->slug = $req->slug;
        // $record->status = $req->status;
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

}
