<?php

class PaystackClient
{
    private $secretKey;
    private $publicKey;

    public function __construct()
    {
        require_once __DIR__ . '/paystack_config.php';
        $config = paystack_config();

        $secretKey = $config['secret_key'] ?? '';
        if (!is_string($secretKey) || trim($secretKey) === '') {
            $secretKey = getenv('PAYSTACK_SECRET_KEY');
        }
        if (!is_string($secretKey) || trim($secretKey) === '') {
            $secretKey = $_SERVER['PAYSTACK_SECRET_KEY'] ?? $_ENV['PAYSTACK_SECRET_KEY'] ?? '';
        }

        if (!is_string($secretKey) || trim($secretKey) === '') {
            throw new RuntimeException('Paystack is not configured. Set the secret_key value in core/paystack_credentials.php or provide PAYSTACK_SECRET_KEY.');
        }

        $this->secretKey = trim($secretKey);

        $publicKey = $config['public_key'] ?? '';
        if (!is_string($publicKey) || trim($publicKey) === '') {
            $publicKey = getenv('PAYSTACK_PUBLIC_KEY');
        }
        if (!is_string($publicKey) || trim($publicKey) === '') {
            $publicKey = $_SERVER['PAYSTACK_PUBLIC_KEY'] ?? $_ENV['PAYSTACK_PUBLIC_KEY'] ?? '';
        }
        $this->publicKey = is_string($publicKey) ? trim($publicKey) : '';
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function initializeTransaction($email, $amountPesewas, $callbackUrl, array $metadata)
    {
        return $this->request('POST', '/transaction/initialize', [
            'email' => $email,
            'amount' => $amountPesewas,
            'currency' => 'GHS',
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
        ]);
    }

    public function verifyTransaction($reference)
    {
        return $this->request('GET', '/transaction/verify/' . rawurlencode($reference));
    }

    private function request($method, $path, array $payload = [])
    {
        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Accept: application/json',
        ];
        $options = [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'timeout' => 25,
            'ignore_errors' => true,
        ];

        if ($method === 'POST') {
            $body = json_encode($payload);
            if ($body === false) {
                throw new RuntimeException('Unable to prepare the payment request.');
            }
            $options['header'] .= "\r\nContent-Type: application/json";
            $options['content'] = $body;
        }

        $context = stream_context_create(['http' => $options]);
        $responseBody = @file_get_contents('https://api.paystack.co' . $path, false, $context);
        if ($responseBody === false) {
            throw new RuntimeException('Unable to contact Paystack. Please try again.');
        }

        $response = json_decode($responseBody, true);
        if (!is_array($response)) {
            throw new RuntimeException('Paystack returned an invalid response.');
        }

        if (empty($response['status'])) {
            $message = isset($response['message']) ? trim($response['message']) : '';
            throw new RuntimeException($message !== '' ? $message : 'Paystack could not process the payment request.');
        }

        if (!isset($response['data']) || !is_array($response['data'])) {
            throw new RuntimeException('Paystack returned an incomplete response.');
        }

        return $response['data'];
    }
}
