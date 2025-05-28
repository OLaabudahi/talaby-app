<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // يجب أن تطبق هذه الواجهة
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User; // إذا كنت تحتاج إلى معلومات المستخدم (مثلاً، لإرسال التقرير إليه)
use App\Models\Order; // للوصول إلى بيانات الطلبات
use Illuminate\Support\Facades\Log; // لتسجيل الأحداث لأغراض التتبع
use Illuminate\Support\Facades\Storage; // لحفظ الملفات (التقارير)
use Illuminate\Support\Facades\Mail; // إذا كنت سترسل التقرير بالبريد الإلكتروني
// use App\Mail\SalesReportMail; // افترض أن لديك Mail Class لإرسال التقرير

class GenerateSalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // متغير لتخزين المستخدم الذي طلب التقرير

    /**
     * إنشاء مثيل جديد للـ job.
     * يتم استدعاء هذه الدالة عند "دفع" الـ job إلى قائمة الانتظار.
     *
     * @param  \App\Models\User  $user  المستخدم الذي طلب التقرير.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * تنفيذ الـ job.
     * يتم استدعاء هذه الدالة عندما يقوم معالج قائمة الانتظار بمعالجة الـ job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("بدء توليد تقرير المبيعات للمستخدم: " . $this->user->email);

        // هنا تضع منطق توليد التقرير المعقد الذي يستغرق وقتًا طويلاً.
        // مثال بسيط: محاكاة عملية طويلة ومعقدة.
        sleep(5); // انتظر 5 ثوانٍ لمحاكاة عمل كثيف.

        // جلب البيانات اللازمة للتقرير
        $servedOrdersCount = Order::where('status', 'served')->count();
        $totalRevenue = Order::where('status', 'served')->sum('total_price');

        // بناء محتوى التقرير
        $reportContent = "=== تقرير المبيعات ===\n";
        $reportContent .= "التاريخ: " . now()->format('Y-m-d H:i:s') . "\n";
        $reportContent .= "--------------------------\n";
        $reportContent .= "عدد الطلبات المكتملة: " . $servedOrdersCount . "\n";
        $reportContent .= "إجمالي الإيرادات من الطلبات المكتملة: " . number_format($totalRevenue, 2) . " دينار.\n";
        $reportContent .= "--------------------------\n";
        $reportContent .= "تم توليد التقرير بواسطة: " . $this->user->name . " (" . $this->user->email . ")\n";

        // حفظ التقرير في ملف
        $filename = 'sales_report_' . now()->format('YmdHis') . '_' . $this->user->id . '.txt';
        Storage::put('reports/' . $filename, $reportContent);

        // بعد الانتهاء من توليد التقرير، يمكنك إرساله عبر البريد الإلكتروني للمستخدم
        // يجب أن يكون لديك Mail Class معد مسبقًا لإرسال البريد
        /*
        try {
            Mail::to($this->user->email)->send(new SalesReportMail($filename));
            Log::info("تم إرسال تقرير المبيعات إلى " . $this->user->email);
        } catch (\Exception $e) {
            Log::error("فشل إرسال تقرير المبيعات إلى " . $this->user->email . ": " . $e->getMessage());
        }
        */

        Log::info("انتهى توليد تقرير المبيعات وحفظه في: storage/app/reports/" . $filename);
    }
}