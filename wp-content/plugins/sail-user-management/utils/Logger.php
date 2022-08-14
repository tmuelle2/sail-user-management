<?php

namespace Sail\Utils;

trait Logger
{
    private int $defaultLogLevel = LOG_INFO;
    private bool $loggerInitialized = false;
    private bool $useErrorLog = true;

    private function initLog(?int $level = NULL, ?bool $useErrorLog = true)
    {
        if (!is_null($level)) {
            if ($level > LOG_CRIT) {
                $level = LOG_CRIT;
                $this->log(LOG_INFO, "Provided default log level is greater than LOG_CRIT ($level), reducing to LOG_CRIT");
            } else if ($level < LOG_DEBUG) {
                $level = LOG_DEBUG;
                $this->log(LOG_INFO, "Provided default log level is less than LOG_DEBUG ($level), increasing to LOG_DEBUG");
            }
            $this->defaultLogLevel = $level;
        }
        if (!is_null($useErrorLog)) {
            $this->useErrorLog = $useErrorLog;
        }
        $this->loggerInitialized = true;
    }

    private function logDebug(string $message): void
    {
        log(LOG_DEBUG, $message);
    }

    private function logInfo(string $message): void
    {
        log(LOG_INFO, $message);
    }

    private function logNotice(string $message): void
    {
        log(LOG_NOTICE, $message);
    }

    private function logWarning(string $message): void
    {
        log(LOG_WARNING, $message);
    }

    private function logError(string $message): void
    {
        log(LOG_ERR, $message);
    }

    private function logCritical(string $message): void
    {
        log(LOG_CRIT, $message);
    }

    private function logAlert(string $message): void
    {
        log(LOG_ALERT, $message);
    }

    private function logEmergency(string $message): void
    {
        log(LOG_EMERG, $message);
    }

    private function log(string $message, ?int $level = null): void
    {
        if ($this->loggerInitialized == false) {
            $this->initLog();
        }
        if ($this->useErrorLog) {
            error_log($message);
        } else {
            $flags = LOG_CONS | LOG_NDELAY | LOG_PID;
            if ((is_null($level) && $this->defaultLogLevel >= LOG_ERR) || $level >= LOG_ERR) {
                $flags = $flags | LOG_PERROR;
            }
            openlog("SailHousingPlugin", $flags, LOG_LOCAL0);
            if (is_null($level)) {
                syslog($this->defaultLogLevel, $message);
            } else {
                syslog($level, $message);
            }
            closelog();
        }
    }
}
