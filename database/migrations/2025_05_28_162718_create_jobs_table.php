<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSalesReport; // استيراد الـ Job Class
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // يجب أن تكون هذه الدالة محمية بحيث لا يمكن الوصول إليها إلا من قبل المستخدمين المصرح لهم (مثل المدراء)
    // يمكنك استخدام middleware('role:admin') في routes/web.php
    public function generateSalesReport()
    {
        // تأكد من أن المستخدم مسجل الدخول قبل إرسال الـ Job
        if (Auth::check()) {
            // أرسل الـ job إلى قائمة الانتظار. سيتم معالجتها في الخلفية بواسطة عامل قائمة الانتظار.
            GenerateSalesReport::dispatch(Auth::user());

            return redirect()->back()->with('success', 'جارٍ توليد التقرير في الخلفية. ستتلقى إشعارًا عند الانتهاء (أو يمكنك التحقق من مجلد التقارير).');
        }

        return redirect()->back()->with('error', 'يجب تسجيل الدخول لتوليد التقرير.');
    }
}