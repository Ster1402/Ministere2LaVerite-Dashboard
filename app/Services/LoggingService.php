<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LoggingService
{
    /**
     * Enregistrer un log dans la base de données.
     *
     * @param string $level Niveau du log (info, warning, error, debug)
     * @param string $message Message du log
     * @param array $context Contexte supplémentaire
     * @param string $channel Canal du log (default: 'application')
     * @return Log
     */
    public static function log($level, $message, $context = [], $channel = 'application')
    {
        $request = request();

        return Log::create([
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'channel' => $channel,
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'uri' => $request->fullUrl(),
        ]);
    }

    /**
     * Enregistrer un log de niveau info.
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return Log
     */
    public static function info($message, $context = [], $channel = 'application')
    {
        return self::log('info', $message, $context, $channel);
    }

    /**
     * Enregistrer un log de niveau warning.
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return Log
     */
    public static function warning($message, $context = [], $channel = 'application')
    {
        return self::log('warning', $message, $context, $channel);
    }

    /**
     * Enregistrer un log de niveau error.
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return Log
     */
    public static function error($message, $context = [], $channel = 'application')
    {
        return self::log('error', $message, $context, $channel);
    }

    /**
     * Enregistrer un log de niveau debug.
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return Log
     */
    public static function debug($message, $context = [], $channel = 'application')
    {
        return self::log('debug', $message, $context, $channel);
    }
}
