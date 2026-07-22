<?php

namespace Dev3bdulrahman\Reports\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Reports\Models\Report;
use Dev3bdulrahman\Reports\Models\ReportExport;
use Dev3bdulrahman\Reports\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all reports.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Report::class);

        $companyId = $request->user()->company_id;
        $perPage = (int) $request->get('per_page', 15);

        $reports = Report::where('company_id', $companyId)
            ->with('creator')
            ->latest()
            ->paginate($perPage);

        return $this->success(
            $reports->items(),
            __('Reports retrieved successfully'),
            200,
            [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ]
        );
    }

    /**
     * Store a new report configuration.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Report::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'filters' => 'nullable|array',
            'schedule' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $report = Report::create([
            'company_id' => $user->company_id,
            'name' => $validated['name'],
            'module' => $validated['module'],
            'type' => $validated['type'],
            'filters' => $validated['filters'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => $user->id,
        ]);

        return $this->success(
            $report,
            __('Report created successfully'),
            201
        );
    }

    /**
     * Show a single report.
     */
    public function show(Report $report): JsonResponse
    {
        $this->authorize('view', $report);

        $report->load(['creator', 'exports']);

        return $this->success(
            $report,
            __('Report details retrieved')
        );
    }

    /**
     * Delete a report.
     */
    public function destroy(Report $report): JsonResponse
    {
        $this->authorize('delete', $report);

        $report->delete();

        return $this->success(
            null,
            __('Report deleted successfully')
        );
    }

    /**
     * Generate (trigger) a report export.
     */
    public function generate(Request $request, Report $report, ReportService $service): JsonResponse
    {
        $this->authorize('view', $report);

        $validated = $request->validate([
            'format' => 'required|in:pdf,excel',
        ]);

        $user = $request->user();

        $export = ReportExport::create([
            'report_id' => $report->id,
            'format' => $validated['format'],
            'file_path' => null,
            'status' => 'pending',
            'exported_by' => $user->id,
        ]);

        return $this->success(
            $export,
            __('Report generation started'),
            202
        );
    }
}
