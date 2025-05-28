<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Menu;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase; // لإعادة تعيين قاعدة البيانات قبل كل اختبار

    /** @test */
    public function an_admin_can_create_a_menu_item()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin); // تسجيل الدخول كمدير

        $response = $this->post('/menus', [
            'name' => 'Test Dish',
            'description' => 'A delicious test dish.',
            'price' => 9.99,
            'category' => 'main',
        ]);

        $response->assertStatus(302); // Redirect بعد الإنشاء
        $this->assertDatabaseHas('menus', ['name' => 'Test Dish']);
    }

    /** @test */
    public function a_waiter_cannot_create_a_menu_item()
    {
        $waiter = User::factory()->create(['role' => 'waiter']);
        $this->actingAs($waiter);

        $response = $this->post('/menus', [
            'name' => 'Test Dish',
            'description' => 'A delicious test dish.',
            'price' => 9.99,
            'category' => 'main',
        ]);

        $response->assertStatus(403); // Unauthorized
        $this->assertDatabaseMissing('menus', ['name' => 'Test Dish']);
    }
    // ... (يمكن إضافة المزيد من الاختبارات لوظائف التحديث، الحذف، العرض)
}