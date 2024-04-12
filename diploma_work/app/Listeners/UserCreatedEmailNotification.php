<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\PasswordResetMail;
use App\Mail\SendUserCreatedMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserCreatedEmailNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        Mail::to($event->user->email)->send(new SendUserCreatedMailable($event->user->name));
    }
}
