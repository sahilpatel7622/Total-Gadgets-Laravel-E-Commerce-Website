<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $setting = Tax::first();

        if (!$setting) {
            $setting = Tax::create([
                'tax_percentage' => 0,
                'delivery_charge' => 0,
                'free_delivery_above' => null,
            ]);
        }

        return view('admin.tax.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tax_percentage'      => 'required|numeric|min:0|max:100',
            'delivery_charge'     => 'required|numeric|min:0',
            'free_delivery_above' => 'nullable|numeric|min:0',
        ]);

        $setting = Tax::first();

        if (!$setting) {
            Tax::create($validated);
        } else {
            $setting->fill($validated);
            if (!$setting->isDirty()) {
                return redirect()
                    ->route('admin.tax.index')
                    ->with('info', 'No changes found.');
            }
            $setting->save();
        }

        return redirect()
            ->route('admin.tax.index')
            ->with('success', 'Tax & Delivery settings updated successfully.');
            
    }
}