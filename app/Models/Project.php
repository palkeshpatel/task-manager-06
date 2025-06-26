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

    public function getTasksCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getCompletedTasksCountAttribute()
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    public function getCompletionPercentageAttribute()
    {
        $totalTasks = $this->tasks_count;
        if ($totalTasks === 0) {
            return 0;
        }
        return round(($this->completed_tasks_count / $totalTasks) * 100, 2);
    }
}
