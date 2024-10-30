<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ImportAdeskCategories extends Command
{
    protected $signature = 'adesk:categories';
    protected $description = 'Import categories from Adesk API and store in cfs_items table';

    public function handle()
    {
        // Получение inflow и outflow групп с максимальным id
        $inflowGroup = DB::table('cfs_item_groups')
            ->where('direction', 'inflow')
            ->orderByDesc('id')
            ->first();

        $outflowGroup = DB::table('cfs_item_groups')
            ->where('direction', 'outflow')
            ->orderByDesc('id')
            ->first();

        if (!$inflowGroup || !$outflowGroup) {
            $this->error('Не найдены группы для inflow или outflow.');
            return Command::FAILURE;
        }

        // Выполнение запроса к API для получения категорий
        $response = Http::get('https://api.adesk.ru/v1/transactions/categories', [
            'api_token' => '67d049bf56eb40acae603e6707021664bd374f3b01094cfb91cae1991a7e2939',
        ]);

        if ($response->failed() || !$response->json('success')) {
            $this->error('Ошибка при запросе к API Adesk');
            return Command::FAILURE;
        }

        // Получение массива категорий
        $categories = $response->json('categories');

        foreach ($categories as $category) {
            // Проверка существования записи в таблице cfs_items по adesk_id
            $existingItem = DB::table('cfs_items')->where('adesk_id', $category['id'])->exists();

            if (!$existingItem) {
                // Определение группы (inflow или outflow) и пользователя (created_by)
                $groupId = $category['type'] === 1 ? $inflowGroup->id : $outflowGroup->id;
                $createdBy = $category['type'] === 1 ? $inflowGroup->created_by : $outflowGroup->created_by;

                // Вставка новой записи в таблицу cfs_items
                DB::table('cfs_items')->insert([
                    'adesk_id' => (int) $category['id'],
                    'name' => $category['name'],
                    'description' => $category['description'] ?? '',
                    'group_id' => $groupId,
                    'created_by' => $createdBy,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $this->info("Категории успешно импортированы в таблицу cfs_items.");
        return Command::SUCCESS;
    }
}

