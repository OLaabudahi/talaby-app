<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * تحديد ما إذا كان المستخدم يستطيع عرض أي صنف قائمة.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // يمكن للمدير والنادل عرض القائمة
        return $user->role === 'admin' || $user->role === 'waiter';
    }

    /**
     * تحديد ما إذا كان المستخدم يستطيع عرض صنف قائمة معين.
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Menu $menu)
    {
        // يمكن للمدير والنادل عرض أي صنف قائمة
        return $user->role === 'admin' || $user->role === 'waiter';
    }

    /**
     * تحديد ما إذا كان المستخدم يستطيع إنشاء أصناف قائمة.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // يمكن للمدير فقط إنشاء أصناف قائمة جديدة
        return $user->role === 'admin';
    }

    /**
     * تحديد ما إذا كان المستخدم يستطيع تحديث صنف قائمة معين.
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Menu $menu)
    {
        // يمكن للمدير فقط تحديث أصناف القائمة
        return $user->role === 'admin';
    }

    /**
     * تحديد ما إذا كان المستخدم يستطيع حذف صنف قائمة معين.
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Menu $menu)
    {
        // يمكن للمدير فقط حذف أصناف القائمة
        return $user->role === 'admin';
    }

    // إذا أردت السماح للمدير بتجاوز جميع الفحوصات
    public function before(User $user, string $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }
}