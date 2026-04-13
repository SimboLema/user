<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppMessage;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle incoming webhook events from 360dialog
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        Log::info('WhatsApp Webhook Received', $request->all());

        // Verify webhook (optional but recommended)
        // This is required when setting up the webhook URL with 360dialog

        $data = $request->all();

        // Handle different event types
        if (isset($data['entry']) && is_array($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                if (isset($entry['changes']) && is_array($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        $this->processChange($change);
                    }
                }
            }
        }

        // Return 200 OK immediately
        return response()->json(['success' => true], 200);
    }

    /**
     * Process individual webhook change
     * 
     * @param array $change
     * @return void
     */
    protected function processChange(array $change): void
    {
        $value = $change['value'] ?? [];

        // Handle incoming messages
        if (isset($value['messages']) && is_array($value['messages'])) {
            foreach ($value['messages'] as $message) {
                $this->handleIncomingMessage($message, $value);
            }
        }

        // Handle message status updates
        if (isset($value['statuses']) && is_array($value['statuses'])) {
            foreach ($value['statuses'] as $status) {
                $this->handleMessageStatus($status, $value);
            }
        }
    }

    /**
     * Handle incoming WhatsApp message
     * 
     * @param array $message
     * @param array $metadata
     * @return void
     */
    protected function handleIncomingMessage(array $message, array $metadata): void
    {
        $messageId = $message['id'] ?? null;
        $fromPhoneNumber = $message['from'] ?? null;
        $timestamp = $message['timestamp'] ?? now();
        $type = $message['type'] ?? 'text';

        // Extract message content based on type
        $messageContent = $this->extractMessageContent($message);

        // Save message to database
        WhatsAppMessage::create([
            'message_id' => $messageId,
            'from_phone' => $fromPhoneNumber,
            'to_phone' => $metadata['metadata']['display_phone_number'] ?? null,
            'type' => $type,
            'content' => $messageContent,
            'direction' => 'inbound',
            'timestamp' => $timestamp,
            'raw_data' => json_encode($message),
        ]);

        // Emit event or dispatch job for further processing
        // Example: dispatch(new ProcessWhatsAppMessage($messageContent, $fromPhoneNumber));

        Log::info('Incoming WhatsApp Message Processed', [
            'from' => $fromPhoneNumber,
            'type' => $type,
            'content' => substr($messageContent, 0, 50),
        ]);
    }

    /**
     * Handle message status updates (sent, delivered, read, failed)
     * 
     * @param array $status
     * @param array $metadata
     * @return void
     */
    protected function handleMessageStatus(array $status, array $metadata): void
    {
        $messageId = $status['id'] ?? null;
        $recipientPhone = $status['recipient_id'] ?? null;
        $statusValue = $status['status'] ?? null; // sent, delivered, read, failed
        $timestamp = $status['timestamp'] ?? now();
        $errors = $status['errors'] ?? [];

        // Update message status in database
        WhatsAppMessage::where('message_id', $messageId)->update([
            'status' => $statusValue,
            'status_updated_at' => $timestamp,
            'errors' => json_encode($errors),
        ]);

        Log::info('WhatsApp Message Status Updated', [
            'message_id' => $messageId,
            'status' => $statusValue,
            'recipient' => $recipientPhone,
        ]);
    }

    /**
     * Extract message content based on message type
     * 
     * @param array $message
     * @return string
     */
    protected function extractMessageContent(array $message): string
    {
        $type = $message['type'] ?? 'text';

        return match ($type) {
            'text' => $message['text']['body'] ?? '',
            'image' => $message['image']['caption'] ?? 'Image message',
            'document' => $message['document']['filename'] ?? 'Document message',
            'audio' => 'Audio message',
            'video' => $message['video']['caption'] ?? 'Video message',
            'location' => sprintf(
                'Location: %.4f, %.4f',
                $message['location']['latitude'] ?? 0,
                $message['location']['longitude'] ?? 0
            ),
            default => 'Message',
        };
    }
}