<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserRegistered;
use App\Models\{
    NotificationType,
    UserProfilePrivacy,
    ProfilePrivacy,
    UserNotificationType
};


class UserProfilePoliciesListener
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
    public function handle(UserRegistered  $event): void
    {
        $user = $event->user;

        $privacyProfileSet  = ["gender", "event_stat", "location", "photo"];

        $checkprivacyProfile = UserProfilePrivacy::where('user_id', $user->id)->count();
        if ($checkprivacyProfile == 0) {

            foreach ($privacyProfileSet as $val) {
                $userNotificationType = new UserProfilePrivacy;
                $userNotificationType->profile_privacy = $val;
                $userNotificationType->user_id = $user->id;
                $userNotificationType->status = '1';
                $userNotificationType->save();
            }
        }


        $notificationSet = [
            "guest_rsvp",
            "private_message",
            "potluck_activity",
            "invitations",
            "wall_post",
        ];

        $checkNotification = UserNotificationType::where('user_id', $user->id)->count();
        if ($checkNotification == 0) {

            foreach ($notificationSet as $valN) {
                $userNotificationType = new UserNotificationType;
                $userNotificationType->type = $valN;
                $userNotificationType->user_id = $user->id;
                $userNotificationType->push = '1';
                $userNotificationType->email = '1';
                $userNotificationType->save();
            }
        }
    }
}
