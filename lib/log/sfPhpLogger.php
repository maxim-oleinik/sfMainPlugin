<?php

/**
 * Пишет в PHP error_log
 */
class sfPhpLogger extends \sfLogger
{
    /**
     * Лог
     *
     * @param string $message   Message
     * @param string $priority  Message priority
     */
    protected function doLog($message, $priority)
    {
        error_log("[{$this->getPriorityName($priority)}] {$message}");
    }

}
