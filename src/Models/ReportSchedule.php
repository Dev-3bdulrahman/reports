<?php

namespace Dev3bdulrahman\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSchedule extends Model
{
    protected $table = 'report_schedules';

    protected $fillable = [
        'report_id',
        'frequency',
        'time',
        'recipients',
        'is_active',
        'last_run_at',
    ];

    protected $casts = [
        'recipients' => 'json',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
