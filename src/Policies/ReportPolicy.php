<?php

namespace Dev3bdulrahman\Reports\Policies;

use App\Models\User;
use Dev3bdulrahman\Reports\Models\Report;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('reports.view');
    }

    public function view(User $user, Report $report): bool
    {
        return $user->can('reports.view') && $report->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('reports.create');
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->can('reports.delete') && $report->company_id === $user->company_id;
    }
}
