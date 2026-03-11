<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Domain;
use App\Models\Topic;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            [
                'name' => 'Flutter',
                'slug' => 'flutter',
                'icon' => '💙',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'C++',
                'slug' => 'cpp',
                'icon' => '🔵',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'iOS',
                'slug' => 'ios',
                'icon' => '🍎',
                'color' => '#000000',
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'icon' => '🔴',
                'color' => '#ef4444',
            ],
        ];

        foreach ($domains as $domain) {
            Domain::firstOrCreate(['slug' => $domain['slug']], $domain);
        }
        
        // Assign existing topics to Flutter if not assigned
        $flutter = Domain::where('slug', 'flutter')->first();
        if ($flutter) {
            Topic::whereNull('domain_id')->update(['domain_id' => $flutter->id]);
        }
    }
}
