<?php

namespace App\Http\Controllers;

use App\Models\TelegramFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;


class TelegramController extends Controller
{
    /**
     * Display a listing of files.
     */
    public function index()
    {
        $files = TelegramFile::latest()->get();
        return view('telegram.index', compact('files'));
    }

    /**
     * Show the form for uploading a new file.
     */
    public function uploadForm()
    {
        return view('telegram.upload');
    }

    /**
     * Store uploaded file to S3 and DB.
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $uploadedFile = $request->file('file');
        $path = Storage::disk('s3')->put('telegram/files', $uploadedFile);

        $file = TelegramFile::create([
            'file_id'       => uniqid('tg_'),
            'file_path'     => $path,
            'mime_type'     => $uploadedFile->getClientMimeType(),
            'original_name' => $uploadedFile->getClientOriginalName(),
        ]);

        return redirect()->route('telegram.index')->with('success', 'File uploaded successfully!');
    }

    /**
     * Display the specified file.
     */
    public function show($id)
    {
        $file = TelegramFile::findOrFail($id);
        return view('telegram.show', compact('file'));
    }

    /**
     * Remove the specified file from S3 and DB.
     */
    public function destroy($id)
    {
        $file = TelegramFile::findOrFail($id);

        Storage::disk('s3')->delete($file->file_path);
        $file->delete();

        return redirect()->route('telegram.index')->with('success', 'File deleted successfully!');
    }

    /**
     * Send a file to Telegram chat.
     */
    public function sendFileToTelegram($id)
    {
        $file = TelegramFile::findOrFail($id);
        // $chatId = '443149454'; // Replace with your Telegram chat ID
        $chatId = '-4199375602'; // Replace with your Telegram Group ID

        // Pull the file content from S3
        $fileContent = Storage::disk('s3')->get($file->file_path);

        // Save to a temporary local file
        $tempPath = storage_path('app/temp/' . basename($file->file_path));
        file_put_contents($tempPath, $fileContent);

        // Wrap it with InputFile
        $telegramFile = InputFile::create($tempPath, $file->original_name);

        // Send to Telegram
        Telegram::sendDocument([
            'chat_id' => $chatId,
            'document' => $telegramFile,
            'caption' => $file->original_name,
        ]);

        // Clean up
        unlink($tempPath);

        return redirect()->route('telegram.index')->with('success', 'File sent to Telegram!');
    }

    /**
     * (Optional) Get raw updates from Telegram.
     */
    public function getUpdates()
    {
        $updates = Telegram::getUpdates();
        return response()->json($updates);
    }

    /**
     * (Optional) Send a test message to Telegram.
     */
    public function sendMessage()
    {
        $chatId = '443149454'; // Replace with your Telegram chat ID
        $message = 'Hello, this is a message from Laravel!';

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        return response()->json(['message' => 'Message sent']);
    }

}
