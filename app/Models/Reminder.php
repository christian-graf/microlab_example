<?php
declare(strict_types=1);

namespace App\Models;
use App\Enums\ReminderIntervals;
use Illuminate\Database\Eloquent\Model;
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
        ];
    }

}
