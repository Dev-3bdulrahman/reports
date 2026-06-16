<?php

namespace Dev3bdulrahman\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ReportExport extends Model
{
    protected $table = 'report_exports';

    protected $fillable = [
        'report_id',
        'format',
        'file_path',
        'status',
        'exported_by',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function exporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
