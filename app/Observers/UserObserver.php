<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (! $user->hasRole('user_default', 'admin')) {
            $role = Role::where('name', 'user_default')
                ->where('guard_name', 'admin')
                ->first();

            if ($role) {
                $user->assignRole($role);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Mail::to(auth()->user())->queue(new FirstEmail('Mensagem do email'));

        // Notification::make()
        //     ->title('Usuário Editado')
        //     ->sendToDatabase(auth()->user(), isEventDispatched: true);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
