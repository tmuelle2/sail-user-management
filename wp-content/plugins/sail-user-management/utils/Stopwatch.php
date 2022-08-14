<?php

namespace Sail\Utils;

/**
 * A trait that can be used for timing and logging events.
 */
trait Stopwatch {
    use Logger;

    private bool $isRunning = false;
    private int $startNanos;
    private int $endNanos;

    private function startStopwatch(): void {
        if ($this->isRunning) {
            throw new \BadFunctionCallException("Cannot start a running Stopwatch");
        }
        $this->isRunning = true;
        $this->startNanos = hrtime(true);
    }

    private function stopStopwatch(): void {
        if ($this->isRunning) {
            $this->isRunning = false;
            $this->endNanos = hrtime(true);
        }
    }

    private function stopwatchElapsedTimeNanos(): int {
        if ($this->isRunning) {
            return hrtime(true) - $this->startNanos;
        }
        return $this->endNanos - $this->startNanos;
    }

    private function stopwatchElapsedTimeMillis(): int {
        if ($this->isRunning) {
            return (hrtime(true) - $this->startNanos) / 1e+6;;
        }
        return ($this->endNanos - $this->startNanos) / 1e+6;
    }

    private function stopStopwatchLogMillis(string $logMessage): void {
        $this->stopStopwatch();
        $this->log("$logMessage {$this->stopwatchElapsedTimeMillis()}ms");
    }
}