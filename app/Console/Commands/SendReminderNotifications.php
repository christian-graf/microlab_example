<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Notifications\EventReminder;
use Illuminate\Database\Query\Builder;

class SendReminderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:send-reminder-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email notification for all expired reminders.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();
        $this->info($today->toDateTimeString());
        // Update all reminders saved without calculated notification date
        $reminders = Reminder::query()
            ->whereNull('next_notification_on')
            ->orWhere('next_notification_on', '<', $today)
            ->get()
        ;
        /** @var Reminder $reminder */
        foreach($reminders as $reminder) {
            $reminder->calculateNextNotificationDate();
            $reminder->save();
        }

        // Send notifications
        $reminders = Reminder::query()
            ->where('next_notification_on', '=', $today)
            ->get()
        ;
        /** @var Reminder $reminder */
        foreach($reminders as $reminder) {
            $reminder->createdBy->notify(new EventReminder($reminder));
            $reminder->setAttribute('last_notification_sent_on', Carbon::now());
            $this->line("Notification for reminder with ID {$reminder->id} sent to user #{$reminder->createdBy->id} successfully.");
            $reminder->save();
        }

        return self::SUCCESS;
    }
}
