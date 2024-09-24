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
                $contractorId = $contractor['id'];
                $contractorName = $contractor['name'];

                // Разбиваем имя на ИНН и Имя
                $inn = null;
                $name = $contractorName;

                // Проверяем, содержит ли строка запятую
                if (strpos($contractorName, ',') !== false) {
                    // Разделение name на ИНН и имя
                    $nameParts = explode(',', $contractorName, 2);
                    $inn = trim($nameParts[0]); // ИНН
                    $name = trim($nameParts[1]); // Имя

                    // Если ИНН не является числом, считаем его невалидным и сбрасываем в null
                    if (!is_numeric($inn)) {
                        $inn = null;
                    }
                }

                // Если у контрагента есть ИНН, проверяем его наличие
                if ($inn) {
                    $existingCompanyByInn = Company::where('inn', $inn)->first();
                    if ($existingCompanyByInn) {
                        // Обновляем adesk_id для записи с совпадающим ИНН
                        $existingCompanyByInn->update([
                            'adesk_id' => $contractorId
                        ]);
                        $this->info("Обновлена запись с ИНН $inn, установлен adesk_id $contractorId.");
                        continue; // Переходим к следующей записи
                    }
                }

                // Проверяем, если запись с таким adesk_id уже существует
                $existingCompanyById = Company::where('adesk_id', $contractorId)->first();

                if (!$existingCompanyById) {
                    // Создаем новую запись в таблице companies
                    Company::create([
                        'inn' => $inn??'0'.now()->getTimestampMs(),
                        'name' => $name,
                        'fullname' => $name,
                        'company_type' => 'other',
                        'our_company' => false,
                        'created_at' => Carbon::now(),
                        'adesk_id' => $contractorId,
                    ]);

                    $this->info("Компания с adesk_id $contractorId добавлена.");
                } else {
                    $this->info("Компания с adesk_id $contractorId уже существует.");
                }
            }
        } else {
            $this->error('Ошибка при выполнении запроса к API');
        }

        return 0;
    }
}
