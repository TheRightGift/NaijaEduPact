<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class TransitionGraduates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transition-graduates';

    protected $description = 'Transition students whose graduation year has passed to the donor role.';

    public function handle()
    {
        $currentYear = Carbon::now()->year;

        $studentsToTransition = User::where('role', 'student')
                                    ->where('expected_graduation_year', '<=', $currentYear)
                                    ->get();

        if ($studentsToTransition->isEmpty()) {
            $this->info('No students to transition.');
            return 0;
        }

        foreach ($studentsToTransition as $student) {
            $student->role = 'donor';
            // Here you can also trigger an event to send them a "Welcome to the Alumni" email!
            $student->save();
        }

        $this->info("Successfully transitioned {$studentsToTransition->count()} students to the donor role.");
        return 0;
    }
}
