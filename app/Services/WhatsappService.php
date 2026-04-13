<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('whatsapp.api_key');
        $this->baseUrl = config('whatsapp.base_url');
        $this->client = new Client();
    }

    /**
     * Send a text message (when customer service window is open)
     * 
     * @param string $recipientPhoneNumber - Phone number in format: countrycode+number (e.g., 255123456789)
     * @param string $messageBody - The message text
     * @return array
     */
    public function sendTextMessage(string $recipientPhoneNumber, string $messageBody): array
    {
        return $this->makeRequest('POST', '/messages', [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'text',
            'text' => [
                'body' => $messageBody,
            ],
        ]);
    }

    /**
     * Send a template message (to initiate conversation)
     * 
     * @param string $recipientPhoneNumber - Phone number
     * @param string $templateName - Pre-approved template name
     * @param string $languageCode - Language code (e.g., 'en_US')
     * @param array $parameters - Optional template parameters
     * @return array
     */
    public function sendTemplateMessage(
        string $recipientPhoneNumber,
        string $templateName,
        string $languageCode = 'en_US',
        array $parameters = []
    ): array {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        // Add parameters if provided
        if (!empty($parameters)) {
            $payload['template']['body'] = [
                'parameters' => $parameters,
            ];
        }

        return $this->makeRequest('POST', '/messages', $payload);
    }

    /**
     * Send a media message (image, document, audio, video)
     * 
     * @param string $recipientPhoneNumber
     * @param string $mediaType - 'image', 'document', 'audio', or 'video'
     * @param string $mediaUrl - URL of the media file
     * @param string|null $caption - Optional caption for the media
     * @return array
     */
    public function sendMediaMessage(
        string $recipientPhoneNumber,
        string $mediaType,
        string $mediaUrl,
        ?string $caption = null
    ): array {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => $mediaType,
            $mediaType => [
                'link' => $mediaUrl,
            ],
        ];

        // Add caption for images and documents
        if ($caption && in_array($mediaType, ['image', 'document'])) {
            $payload[$mediaType]['caption'] = $caption;
        }

        return $this->makeRequest('POST', '/messages', $payload);
    }

    /**
     * Make HTTP request to WhatsApp API
     * 
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    protected function makeRequest(string $method, string $endpoint, array $data): array
    {
        try {
            $response = $this->client->request($method, $this->baseUrl . $endpoint, [
                'headers' => [
                    'D360-API-KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            Log::info('WhatsApp API Request Successful', [
                'endpoint' => $endpoint,
                'response' => $responseBody,
            ]);

            return [
                'success' => true,
                'data' => $responseBody,
                'status_code' => $response->getStatusCode(),
            ];
        } catch (GuzzleException $exception) {
            $errorMessage = $exception->getMessage();
            $errorResponse = [];

            if ($exception->hasResponse()) {
                $errorResponse = json_decode($exception->getResponse()->getBody()->getContents(), true);
            }

            Log::error('WhatsApp API Request Failed', [
                'endpoint' => $endpoint,
                'error' => $errorMessage,
                'response' => $errorResponse,
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'data' => $errorResponse,
            ];
        }
    }
}