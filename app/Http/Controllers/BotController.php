<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class BotController extends Controller
{
    protected $telegram;

    /**
     * Create a new controller instance.
     *
     * @param  Api  $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Show the bot information.
     */
    public function show(): JsonResponse
    {
        try {
            $response = $this->telegram->getMe();
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle incoming webhook updates.
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            // Process incoming update
            $update = $this->telegram->commandsHandler(true);
            
            // Log the update for debugging
            \Log::info('Telegram Update:', $update->toArray());
            
            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            \Log::error('Telegram Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Set webhook URL.
     */
    public function setWebhook(Request $request): JsonResponse
    {
        try {
            $url = $request->input('url', config('app.url') . '/telegram/webhook');
            
            $response = $this->telegram->setWebhook([
                'url' => $url,
                'allowed_updates' => ['message', 'callback_query', 'inline_query']
            ]);

            return response()->json([
                'success' => true,
                'webhook_url' => $url,
                'response' => $response
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove webhook.
     */
    public function removeWebhook(): JsonResponse
    {
        try {
            $response = $this->telegram->removeWebhook();
            return response()->json(['success' => true, 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get webhook info.
     */
    public function getWebhookInfo(): JsonResponse
    {
        try {
            $response = $this->telegram->getWebhookInfo();
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a test message.
     */
    public function sendTestMessage(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required',
            'message' => 'required|string'
        ]);

        try {
            $response = $this->telegram->sendMessage([
                'chat_id' => $request->chat_id,
                'text' => $request->message,
                'parse_mode' => 'HTML'
            ]);

            return response()->json(['success' => true, 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get bot updates (for testing without webhook).
     */
    public function getUpdates(): JsonResponse
    {
        try {
            $updates = $this->telegram->getUpdates([
                'limit' => 10,
                'timeout' => 0
            ]);

            return response()->json($updates);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send photo message.
     */
    public function sendPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required',
            'photo' => 'required|string',
            'caption' => 'nullable|string'
        ]);

        try {
            $response = $this->telegram->sendPhoto([
                'chat_id' => $request->chat_id,
                'photo' => $request->photo,
                'caption' => $request->caption ?? '',
                'parse_mode' => 'HTML'
            ]);

            return response()->json(['success' => true, 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send document.
     */
    public function sendDocument(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required',
            'document' => 'required|string',
            'caption' => 'nullable|string'
        ]);

        try {
            $response = $this->telegram->sendDocument([
                'chat_id' => $request->chat_id,
                'document' => $request->document,
                'caption' => $request->caption ?? ''
            ]);

            return response()->json(['success' => true, 'response' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle inline keyboard callback.
     */
    public function handleCallback(Request $request): JsonResponse
    {
        try {
            $update = $this->telegram->getWebhookUpdate();
            
            if ($update->has('callback_query')) {
                $callbackQuery = $update->getCallbackQuery();
                $data = $callbackQuery->getData();
                $chatId = $callbackQuery->getMessage()->getChat()->getId();
                
                // Answer the callback query
                $this->telegram->answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                    'text' => 'Button clicked!'
                ]);

                // Handle different callback data
                switch ($data) {
                    case 'button_1':
                        $this->telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'You clicked Button 1!'
                        ]);
                        break;
                    case 'button_2':
                        $this->telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'You clicked Button 2!'
                        ]);
                        break;
                    default:
                        $this->telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'Unknown button clicked.'
                        ]);
                }
            }

            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            \Log::error('Callback handling error: ' . $e->getMessage());
            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }
}