<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Company;
use Carbon\Carbon;

class AdeskFetchAndStoreCompanies extends Command
{
    protected $signature = 'adesk:companies';
    protected $description = 'Получить контрагентов из adesk';

    public function handle()
    {
        // Получаем токен из файла .env
        $apiToken = env('ADESK_TOKEN');

        // URL для запроса с токеном из .env
        $url = "https://api.adesk.ru/v1/contractors?api_token={$apiToken}";

        // Выполняем запрос
        $response = Http::get($url);

        if ($response->successful()) {
            $contractors = $response->json('contractors');

            foreach ($contractors as $contractor) {
                $contractorName = $contractor['name'];

                // Проверяем, содержит ли строка запятую
                if (strpos($contractorName, ',') !== false) {
                    // Разделение name на ИНН и имя
                    $nameParts = explode(',', $contractorName, 2);
                    $inn = trim($nameParts[0]); // ИНН
                    $name = trim($nameParts[1]); // Имя

                    // Проверяем, если ИНН состоит только из цифр
                    if (is_numeric($inn)) {
                        // Проверка, существует ли запись в таблице companies
                        $existingCompany = Company::where('inn', $inn)->first();

                        if (!$existingCompany) {
                            // Создаем новую запись
                            Company::create([
                                'inn' => $inn,
                                'name' => $name,
                                'fullname' => $name,
                                'company_type' => 'other',
                                'our_company' => false,
                                'created_at' => Carbon::now(),
                            ]);

                            $this->info("Компания с ИНН $inn добавлена.");
                        } else {
                            $this->info("Компания с ИНН $inn уже существует.");
                        }
                    } else {
                        $this->info("Неправильный формат ИНН: $inn. Пропуск записи.");
                    }
                } else {
                    // Пропускаем запись без запятой
                    $this->info("Пропуск записи без ИНН: {$contractorName}");
                }
            }
        } else {
            $this->error('Ошибка при выполнении запроса к API');
        }

        return 0;
    }
}
