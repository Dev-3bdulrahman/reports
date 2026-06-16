<?php

namespace Dev3bdulrahman\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportFilter extends Model
{
    protected $table = 'report_filters';

    protected $fillable = [
        'report_id',
        'field',
        'operator',
        'value',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
