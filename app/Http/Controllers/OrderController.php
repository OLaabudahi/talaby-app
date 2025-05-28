<?php

namespace App\Http\Controllers;

use App\Models\Order; // تأكد من استيراد نموذج Order
use App\Models\Table;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ... دالة allOrders()

    /**
     * عرض الطلبات المعلقة للمستخدم الحالي (للنادل) مع التحميل المسبق.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // هنا نضع كود Eager Loading
        // 'table': لتحميل علاقة الطاولة
        // 'orderItems.menu': لتحميل عناصر الطلب وكل قائمة المنيو المرتبطة بكل عنصر طلب.
        $orders = Auth::user()->orders()->where('status', 'pending')->with(['table', 'orderItems.menu'])->get();
        return view('orders.index', compact('orders'));
    }

    // ... باقي الدوال الأخرى للمتحكم

   public function show(Order $order)
    {
    // $this->authorize('view', $order); // إذا كنت تستخدم OrderPolicy

    $order->load(['table', 'user', 'orderItems.menu']); // الدالة `load()` تعمل على نموذج موجود

    $menus = Menu::all(); // لعرض قائمة بالعناصر المتاحة للإضافة
    return view('orders.show', compact('order', 'menus'));
    }

}
