<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   // ... (باقي الكود)

public function store(Request $request)
{
    $validated = $request->validate([
        'table_id' => 'required|exists:tables,id',
        'items' => 'required|array|min:1',
        'items.*.menu_id' => 'required|exists:menus,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $order = new Order();
    $order->table_id = $validated['table_id'];
    $order->user_id = auth()->id(); // المستخدم الحالي هو من أنشأ الطلب
    $order->status = 'pending';
    $order->total_price = 0; // سيتم تحديثه لاحقًا
    $order->save();

    $totalPrice = 0;
    foreach ($validated['items'] as $itemData) {
        $menuItem = Menu::find($itemData['menu_id']);
        if ($menuItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menuItem->id,
                'quantity' => $itemData['quantity'],
                'price' => $menuItem->price, // سعر الصنف وقت الطلب
            ]);
            $totalPrice += $menuItem->price * $itemData['quantity'];
        }
    }

    $order->total_price = $totalPrice;
    $order->save();

    return redirect()->route('orders.show', $order->id)->with('success', 'Order created successfully!');
}

// دالة لتغيير حالة الطلب
public function updateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,served,canceled',
    ]);

    $order->status = $validated['status'];
    $order->save();

    return redirect()->back()->with('success', 'Order status updated successfully.');
}

// دالة لعرض لوحة إدارة الطلبات
public function dashboard()
{
    $pendingOrders = Order::where('status', 'pending')->with('table', 'user', 'orderItems.menu')->get();
    $servedOrders = Order::where('status', 'served')->with('table', 'user', 'orderItems.menu')->get();
    $canceledOrders = Order::where('status', 'canceled')->with('table', 'user', 'orderItems.menu')->get();

    // إحصائيات بسيطة
    $dailyOrdersCount = Order::whereDate('created_at', today())->count();
    // يمكنك إضافة منطق لأكثر الأصناف طلباً هنا
    $topMenuItems = OrderItem::selectRaw('menu_id, SUM(quantity) as total_quantity')
                        ->groupBy('menu_id')
                        ->orderByDesc('total_quantity')
                        ->limit(5)
                        ->with('menu')
                        ->get();


    return view('orders.dashboard', compact('pendingOrders', 'servedOrders', 'canceledOrders', 'dailyOrdersCount', 'topMenuItems'));
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
