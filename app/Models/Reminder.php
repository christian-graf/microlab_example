<?php
declare(strict_types=1);

namespace App\Models;
use Illuminate\Support\Carbon;
use App\Enums\ReminderIntervals;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Reminder extends Model
{
    use HasTimestamps;

    /**
     * Name of the designated database table.
     *
     * @var string
     */
    protected $table = 'reminders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'day',
        'month',
        'description',
        'interval',
    ];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saving(function (Reminder $reminder) {
            $reminder->calculateNextNotificationDate();
        });
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'day' => 'integer',
            'month' => 'integer',
            'description' => 'string',
            'interval' => ReminderIntervals::class,
            'created_by' => 'integer',
            'last_notification_sent_on' => 'date',
            'next_notification_on' => 'date',
        ];
    }

    /**
     * Calculates the next date when the user has to be notified.
     *
     * @return void
     */
    public function calculateNextNotificationDate(): void
    {
        $nextNotificationOn = null;
        if ($this->hasAttribute('day') && $this->hasAttribute('month') && $this->hasAttribute('interval')) {
            $nextNotificationOn = Carbon::create(
                Carbon::now()->year,
                (int) $this->getAttribute('month'),
                (int) $this->getAttribute('day'),
                0,
                0
            );

            switch($this->hasAttribute('interval')) {
                case '1D':
                    $nextNotificationOn->subDay();
                    break;
                case '2D':
                    $nextNotificationOn->subDays(2);
                    break;
                case '4D':
                    $nextNotificationOn->subDays(4);
                    break;
                case '1W':
                    $nextNotificationOn->subWeek();
                    break;
                case '2W':
                    $nextNotificationOn->subWeeks(2);
                    break;
            }

            if ($nextNotificationOn <= Carbon::now()) {
                $nextNotificationOn->addYear();
            }
        }

        $this->setAttribute('next_notification_on', $nextNotificationOn);
    }

    /**
     * Magic relationship method for "createdBy"
     * Receive the related user which has created this reminder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Magic attribute method for "intervalText"
     * Get a better human-readable interval text.
     *
     * @return string
     */
    protected function getIntervalTextAttribute(): string
    {
        switch($this->interval) {
            case ReminderIntervals::ONE_DAY:
                return '1 day';
            case ReminderIntervals::TWO_DAYS:
                return '2 days';
            case ReminderIntervals::FOUR_DAYS:
                return '4 days';
            case ReminderIntervals::ONE_WEEK:
                return '1 week';
            case ReminderIntervals::TWO_WEEKS:
                return '2 weeks';
            default:
                return 'some days';
        }
    }
}
