<?php

namespace Dev3bdulrahman\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Report extends Model
{
    use BelongsToCompany;

    protected $table = 'reports';

    protected $fillable = [
        'company_id',
        'name',
        'module',
        'type',
        'filters',
        'schedule',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'filters' => 'json',
        'schedule' => 'json',
        'is_active' => 'boolean',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function exports(): HasMany
    {
        return $this->hasMany(ReportExport::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
