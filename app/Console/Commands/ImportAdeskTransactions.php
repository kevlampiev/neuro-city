<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class ImportAdeskTransactions extends Command
{
    protected $signature = 'adesk:import-transactions 
    {--startDate=2020-01-01 : Дата начала импорта в формате YYYY-MM-DD} 
    {--endDate= : Дата окончания импорта в формате YYYY-MM-DD (по умолчанию - текущая дата)}';

    protected $description = 'Import transactions from Adesk API and store in import_adesk_operations table';

    public function handle()
    {

        $startDate = Carbon::parse($this->option('startDate'))->format('Y-m-d');
        $endDate = $this->option('endDate') 
            ? Carbon::parse($this->option('endDate'))->format('Y-m-d') 
            : Carbon::now()->format('Y-m-d');

        // Очистка таблицы перед вставкой новых данных
        DB::table('import_adesk_operations')->truncate();

        // Выполнение HTTP-запроса к API
        $response = Http::get('https://api.adesk.ru/v1/transactions', [
            'status' => 'completed',
            'api_token' => '67d049bf56eb40acae603e6707021664bd374f3b01094cfb91cae1991a7e2939',
            'range' => 'custom',
            'range_start' => $startDate,
            'range_end' => $endDate,
        ]);

        // Проверка ответа API
        if ($response->failed() || !$response->json('success')) {
            $this->error('Ошибка при запросе к API Adesk');
            return Command::FAILURE;
        }

        // Получение массива транзакций
        $transactions = $response->json('transactions');
        
        // Обработка и вставка каждой транзакции в таблицу
        foreach ($transactions as $transaction) {
            DB::table('import_adesk_operations')->insert([
                'adesk_id' => (int) $transaction['id'],
                'adesk_type_operation_code' => (int) $transaction['type'],
                'amount' => (float) $transaction['amount'],
                'date_open' => \Carbon\Carbon::createFromFormat('d.m.Y', $transaction['date'])->format('Y-m-d'),
                'adesk_bank_account_id' => (int) $transaction['bankAccount']['id'],
                'adesk_bank_name' => $transaction['bankAccount']['bankName'],
                'adesk_company_id' => (int) $transaction['bankAccount']['legalEntity']['id'],
                'adesk_company_name' => $transaction['bankAccount']['legalEntity']['name'],
                'description' => $transaction['description'],
                'adesk_cfs_category_id' => isset($transaction['category']['id']) ? (int) $transaction['category']['id'] : null,
                'adesk_cfs_category_name' => $transaction['category']['name'] ?? null,
                'adesk_contractor_id' => isset($transaction['contractor']['id']) ? (int) $transaction['contractor']['id'] : null,
                'adesk_contractor_name' => $transaction['contractor']['name'] ?? null,
            ]);
        }

        $this->info('Транзакции успешно импортированы в таблицу import_adesk_operations.');
        return Command::SUCCESS;
    }
}
