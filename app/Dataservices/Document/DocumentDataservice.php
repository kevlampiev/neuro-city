<?php


namespace App\Dataservices\Document;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class DocumentDataservice
{
    /**
     * Сохраняет новый файл и регистрирует его в таблице documents.
     */
    public static function saveFile(Request $request, Document $document = null): Document
    {
        if (!$document) {
            $document = new Document();
            $document->created_by = Auth::id();
            $document->created_at = now();
        } else {
            $document->updated_at = now();
            $document->id = $request->input('id');
        }

        // Заполняем атрибуты модели, исключая файл
        $document->description = $request->input('description');

        // Если файл присутствует в запросе, обрабатываем его
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = self::storeFile($file);
            $document->file_name = $filename;
        }

        // Сохраняем документ в базе
        $document->save();

        return $document;
    }

    /**
     * Загружает файл на диск и возвращает его имя.
     */
    private static function storeFile($file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs(config('paths.documents.put', 'public/documents'), $filename);
        return $filename;
    }

    /**
     * Сохраняет несколько файлов, загруженных пользователем.
     */
    public static function saveMultipleFiles(Request $request): array
    {
        $savedDocuments = [];

        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $document = new Document();
                $document->name = $file->getClientOriginalName(); // Устанавливаем оригинальное имя файла
                $document->file_name = self::storeFile($file); // Сохраняем файл и получаем UUID
                $document->created_by = Auth::id();
                $document->created_at = now();
                $document->save();

                $savedDocuments[] = $document;
            }
        }

        return $savedDocuments;
    }

    /**
     * Обновляет существующий документ.
     */
    public static function updateDocument(Request $request, Document $document): bool
    {
        try {
            // Проверяем наличие нового файла и обновляем документ
            self::saveFile($request, $document);
            return true;
        } catch (\Throwable $exception) {
            // Здесь можно добавить логирование
            return false;
        }
    }
}