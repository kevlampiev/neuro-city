<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Agreement;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class AgreementDocumentTest extends TestCase
{
    /**
     * Тест загрузки документа на маршрут `agreement/{agreement}/addDocument`.
     */
    public function test_can_upload_document_to_agreement()
    {
        Storage::fake('documents'); // Фейковое хранилище для тестирования

        // Создаем пользователя и авторизуем
        $user = User::query()
        ->where('is_superuser', '=', true)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->first();

        $this->actingAs($user);

        // Создаем произвольный договор
        $agreement = Agreement::inRandomOrder()->first();

        // Загружаемый файл
        $file = UploadedFile::fake()->create('test-document.pdf', 100, 'application/pdf');

        // Выполняем POST-запрос для загрузки файла
        $response = $this->post(route('addAgreementDocument', $agreement), [
            'document_file' => $file,
        ]);

        // Проверяем, что запрос успешен
        $response->assertStatus(302)
        ->assertSee('Карточка договора');

        // Проверяем, что файл загружен в фейковое хранилище
        Storage::disk('documents')->assertExists($file->hashName());

        // Проверяем, что запись о документе создана в БД
        $this->assertDatabaseHas('documents', [
            'file_name' => $file->hashName(),
        ]);
    }

    /**
     * Тест отображения загруженного документа и его описания на странице договора.
     */
    public function test_uploaded_document_is_visible_in_agreement_summary()
    {
        // Создаем пользователя и авторизуем
        $user = User::query()
            ->where('is_superuser', '=', true)
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->first();
        $this->actingAs($user);

        // Создаем договор
        $agreement = Agreement::inRandomOrder()->first();

        // Создаем документ и связываем с договором
        $document = Document::factory()->create([
            'file_name' => 'test-document.pdf',
            'description' => 'Тестовое описание',
        ]);
        $agreement->documents()->attach($document);

        // Выполняем GET-запрос на карточку договора
        $response = $this->get(route('agreementSummary', [
            'agreement' => $agreement->id,
            'page' => 'documents',
        ]));

        // Проверяем успешный статус запроса
        $response->assertStatus(200);

        // Проверяем, что на странице отображается имя и описание документа
        $response->assertSee('test-document.pdf');
        $response->assertSee('Тестовое описание');
    }
}
