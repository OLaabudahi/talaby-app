<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // تأكد من استيراد الموديل

class MenuController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من صحة البيانات مباشرة في المتحكم
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:main,starter,drink,dessert', // يمكن تحديد الفئات المسموح بها
            'image_path' => 'nullable|string', // أو 'image|mimes:jpeg,png,jpg,gif|max:2048' إذا كان رفع صور
        ]);

        Menu::create($validatedData);

        return redirect()->route('menus.index')->with('success', 'تمت إضافة العنصر بنجاح!');
    }

    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:main,starter,drink,dessert',
            'image_path' => 'nullable|string',
        ]);

        $menu->update($validatedData);

        return redirect()->route('menus.index')->with('success', 'تم تحديث العنصر بنجاح!');
    }
}