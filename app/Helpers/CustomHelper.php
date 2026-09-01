<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Stevebauman\Purify\Facades\Purify;

class CustomHelper
{


    public static function maskEmail($email)
    {
        // Replace @ with [at] and . with [dot]
        $masked = str_replace('@', '[at]', $email);
        $masked = str_replace('.', '[dot]', $masked);
        return $masked;
    }

    public static function truncate_words($text, $limit = 100, $end = '...')
    {

        // Convert multiple spaces, tabs, newlines to single space
        $text = preg_replace('/\s+/', ' ', $text);

        // Split text into words
        $words = explode(' ', trim($text));

        // If word count is less than limit, return original
        if (count($words) <= $limit) {
            return implode(' ', $words);
        }

        // Return truncated string with optional ending
        return implode(' ', array_slice($words, 0, $limit)) . $end;
    }

    public static function truncate_html($html, $limit = 150, $ellipsis = '...'): string
    {
        // Convert to UTF-8 and clean input
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML('<div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('div')->item(0);
        if (!$body) {
            return \Illuminate\Support\Str::words(strip_tags($html), $limit, $ellipsis);
        }

        $truncated = '';
        $currentLength = 0;

        foreach ($body->childNodes as $node) {
            if ($currentLength >= $limit) {
                break;
            }

            $truncated .= self::truncate_node($node, $limit, $currentLength, $ellipsis);
        }

        return $truncated;
    }

    protected static function truncate_node(\DOMNode $node, $limit, &$count, $ellipsis): string
    {
        $html = '';

        if ($node->nodeType === XML_TEXT_NODE) {
            $words = preg_split('/\s+/u', $node->nodeValue, -1, PREG_SPLIT_NO_EMPTY);
            $remaining = $limit - $count;

            if ($remaining <= 0) {
                return '';
            }

            if (count($words) > $remaining) {
                $html .= implode(' ', array_slice($words, 0, $remaining)) . $ellipsis;
                $count += $remaining;
            } else {
                $html .= implode(' ', $words) . ' ';
                $count += count($words);
            }
        } elseif ($node->nodeType === XML_ELEMENT_NODE) {
            $html .= '<' . $node->nodeName;

            if ($node->hasAttributes()) {
                foreach ($node->attributes as $attr) {
                    $html .= ' ' . $attr->nodeName . '="' .
                        htmlspecialchars($attr->nodeValue, ENT_QUOTES | ENT_XML1, 'UTF-8') . '"';
                }
            }

            $html .= '>';

            foreach ($node->childNodes as $child) {
                if ($count >= $limit)
                    break;
                $html .= self::truncate_node($child, $limit, $count, $ellipsis);
            }

            $html .= '</' . $node->nodeName . '>';
        }

        return $html;
    }

    public static function setEncryptionKey()
    {
        $key = bin2hex(random_bytes(16)); // 128-bit AES key
        session(['encryption_key' => $key]);
        return $key;
    }

    public static function encryptData($data)
    {
        $sessionKey = session('encryption_key');
        if (empty($sessionKey)) {
            $sessionKey = self::setEncryptionKey();
        }
        $key = hex2bin($sessionKey);
        $iv = random_bytes(12);
        $tag = '';
        $encrypted = openssl_encrypt($data, 'aes-128-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return base64_encode($iv . $encrypted . $tag);
    }

    public static function decryptPassword($cipherTextBase64)
    {
        $sessionKey = session('encryption_key');
        if (empty($sessionKey)) {
            return false;
        }

        try {
            $key = hex2bin($sessionKey);
            $decoded = base64_decode($cipherTextBase64);

            if ($decoded === false || strlen($decoded) < 28) {
                return false;
            }

            $iv = substr($decoded, 0, 12);
            $tag = substr($decoded, -16);
            $cipher = substr($decoded, 12, -16);

            $decrypted = openssl_decrypt(
                $cipher,
                'aes-128-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            return $decrypted;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function decryptData($cipherTextBase64)
    {
        return self::decryptPassword($cipherTextBase64);
    }
}
