<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

        if (!file_exists($credentialsPath)) {
            Log::error('[Firebase] Credentials file not found: ' . $credentialsPath);
            $this->messaging = null;
            return;
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath);

            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('[Firebase] Init failed: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send push notification to a single device token.
     * Returns true on success, false on failure.
     */
    public function sendNotification(string $token, string $title, string $body): bool
    {
        if (!$this->messaging) {
            Log::warning('[Firebase] Messaging not initialized — skipping notification.');
            return false;
        }

        try {
            $message = CloudMessage::new()
                ->withToken($token)
                ->withNotification(Notification::create($title, $body));

            $this->messaging->send($message);

            Log::info('[Firebase] Notification sent', [
                'token' => substr($token, 0, 20) . '...',
                'title' => $title,
            ]);

            return true;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Token is invalid/expired — should be deleted
            Log::warning('[Firebase] Token not found (expired/invalid)', [
                'token' => substr($token, 0, 20) . '...',
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('[Firebase] Send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to multiple tokens at once.
     */
    public function sendToMultiple(array $tokens, string $title, string $body): array
    {
        $results = ['success' => 0, 'failed' => 0, 'invalid_tokens' => []];

        foreach ($tokens as $token) {
            $sent = $this->sendNotification($token, $title, $body);
            if ($sent) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['invalid_tokens'][] = $token;
            }
        }

        return $results;
    }
}