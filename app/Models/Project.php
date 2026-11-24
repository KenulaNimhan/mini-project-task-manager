<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function completedCount(): int
    {
        return $this->tasks()
            ->where('is_completed', true)
            ->get()->count();

    }

    public function completedPercentage(): float
    {
        $total_tasks = $this->tasks->count();
        $completed_count = $this->tasks()
            ->where('is_completed', true)
            ->get()->count();

        return ($completed_count / $total_tasks) * 100;

    }
}
