<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\MenuPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // تأكد من استيراد Gate

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Menu::class => MenuPolicy::class, // أضف هذا السطر
        // يمكنك إضافة Policies أخرى هنا
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // يمكنك تعريف Gates هنا أيضًا
    }
}