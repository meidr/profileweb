<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateUniqueCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-unique-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate 5-digit unique codes for employees who do not have one.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = \App\Models\Employee::whereNull('unique_code')->get();
        $count = 0;
        foreach ($employees as $employee) {
            do {
                $code = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
            } while (\App\Models\Employee::where('unique_code', $code)->exists());
            
            $employee->unique_code = $code;
            $employee->save();
            $count++;
        }
        
        $this->info("Generated unique codes for $count employees.");
    }
}
