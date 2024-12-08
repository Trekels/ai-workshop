<?php

declare(strict_types=1);

namespace App\TogetherAI;

final class ResponseParser
{
    public function parse(string $responseBody): string
    {
        return trim(array_reduce(explode('data: ', $responseBody), function (string $result, string $chunk) {
            $chunk = trim($chunk);

            // Skip empty lines or the "[DONE]" signal
            if (empty($chunk) || $chunk === '[DONE]') {
                return $result;
            }

            $decoded = json_decode($chunk, true);
            if (json_last_error() !== JSON_ERROR_NONE && !isset($decoded['choices'][0]['text'])) {
                throw new \RuntimeException('Invalid chunk');
            }

            return $result . $decoded['choices'][0]['text'];
        }, ''));
    }
}