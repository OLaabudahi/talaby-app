<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرات.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // إضافة فهرس لعمود 'status' في جدول 'orders' لتحسين استعلامات الحالة.
            $table->index('status');
        });

        Schema::table('menus', function (Blueprint $table) {
            // إضافة فهرس لعمود 'category' في جدول 'menus' لتحسين استعلامات الفئة.
            $table->index('category');
        });
    }

    /**
     * التراجع عن الهجرات.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']); // حذف الفهرس عند التراجع
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex(['category']); // حذف الفهرس عند التراجع
        });
    }
};