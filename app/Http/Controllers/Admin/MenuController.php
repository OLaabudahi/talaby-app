<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:starter,main,drink,dessert',
            'image_path' => 'nullable|image|max:2048', // Example validation
        ]);

        $menu = new Menu($request->all());
        if ($request->hasFile('image_path')) {
            $menu->image_path = $request->file('image_path')->store('menus', 'public');
        }
        $menu->save();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:starter,main,drink,dessert',
            'image_path' => 'nullable|image|max:2048', // Example validation
        ]);

        $menu->fill($request->all());
        if ($request->hasFile('image_path')) {
            // Delete old image if exists
            if ($menu->image_path) {
                \Storage::disk('public')->delete($menu->image_path);
            }
            $menu->image_path = $request->file('image_path')->store('menus', 'public');
        }
        $menu->save();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image_path) {
            \Storage::disk('public')->delete($menu->image_path);
        }
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu item deleted successfully.');
    }
}