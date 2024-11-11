<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Carbon\Carbon;

class ImportAdeskProjects extends Command
{
    protected $signature = 'adesk:projects';
    protected $description = 'Import projects from Adesk API and store in projects table';

    public function handle()
    {
        // Выполнение запроса к API для получения проектов
        $response = Http::get('https://api.adesk.ru/v1/projects', [
            'api_token' => env('ADESK_TOKEN'),
        ]);

        if ($response->failed() || !$response->json('success')) {
            $this->error('Ошибка при запросе к API Adesk');
            return Command::FAILURE;
        }

        // Получение массива проектов
        $projects = $response->json('projects');
        $user = User::where('is_superuser', true)->first();

        foreach ($projects as $project) {
            // Проверка существования записи в таблице projects по adesk_id
            $existingProject = DB::table('projects')->where('adesk_id', $project['id'])->exists();

            if (!$existingProject) {
                // Вставка новой записи в таблицу projects
                DB::table('projects')->insert([
                    'adesk_id' => (int) $project['id'],
                    'name' => $project['name'],
                    'description' => $project['description'] ?? '',
                    'created_at' => Carbon::now(),
                    'created_by' => $user->id,
                ]);
            }
        }

        $this->info("Проекты успешно импортированы в таблицу projects.");
        return Command::SUCCESS;
    }
}
