<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class CheckOverdueTasks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
public function handle()
{
    // Fetch tasks that could be overdue
    $tasks = Task::where('status', '!=', 'DONE')
        ->where('due_date', '<', now())
        ->get(['id', 'due_date', 'status'])
        ->map(function ($task) {
            return [
                'id' => $task->id,
                'due_date' => $task->due_date->toDateString(),
                'status' => $task->status,
            ];
        })
        ->values()
        ->toArray();

    if (empty($tasks)) {
        return;
    }

    // Call Django API
    $response = Http::post(
        config('services.django.overdue_url'),
        ['tasks' => $tasks]
    );

    if (!$response->successful()) {
        logger()->error('Django overdue service failed', [
            'response' => $response->body()
        ]);
        return;
    }

    $overdueIds = $response->json('overdue_task_ids', []);

    if (!empty($overdueIds)) {
        Task::whereIn('id', $overdueIds)
            ->update(['status' => 'OVERDUE']);
    }
}
}

