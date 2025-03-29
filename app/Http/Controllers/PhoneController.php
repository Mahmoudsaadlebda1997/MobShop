<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhoneController extends Controller
{
    public function site()
    {
        $phones = Phone::all();
        return view('site', compact('phones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'category_name', 'price');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('phones', 'public');
        }

        Phone::create($data);

        return response()->json(['message' => 'Phone added successfully']);
    }

    public function update(Request $request, $id)
    {
        $phone = Phone::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'category_name', 'price');

        if ($request->hasFile('image')) {
            if ($phone->image) {
                Storage::disk('public')->delete($phone->image);
            }
            $data['image'] = $request->file('image')->store('phones', 'public');
        }

        $phone->update($data);

        return response()->json(['message' => 'Phone updated successfully']);
    }

    public function destroy($id)
    {
        $phone = Phone::findOrFail($id);

        if ($phone->image) {
            Storage::disk('public')->delete($phone->image);
        }

        $phone->delete();

        return response()->json(['message' => 'Phone deleted successfully']);
    }
}
