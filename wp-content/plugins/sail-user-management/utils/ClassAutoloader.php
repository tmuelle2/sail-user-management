<?php

namespace Sail\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Sail\Caching\DatabaseCache;
use Sail\Constants;

class ClassAutoloader
{
    use DatabaseCache;
    use Logger;
    use Singleton;
    use Stopwatch;

    private $classPathMap = array();
    private const CACHE_KEY = "classpath";

    public function updateCachedClasspath(): void
    {
        if (!empty($this->classPathMap)) {
            return;
        }

        //$this->startStopwatch();
        $dirIter = new RecursiveDirectoryIterator(Constants::HOME_DIR);
        $iter = new RecursiveIteratorIterator($dirIter);
        $libPaths = new RegexIterator($iter, '/^.*\.php$/m', RegexIterator::MATCH);

        $namespaceRegex =  '/namespace (.*);/';
        $functionRegex =  '/function (.*)\(.*/';
        foreach ($libPaths as $file) {
            $path = $file->getPathName();
            $split = explode('/', $path);
            $fileNameWithExt = end($split);
            $justFileName = basename($fileNameWithExt, '.php');
            $fileContents = file_get_contents($path);
            // This loads full namespaced classes assuming file name and class name matches
            if (preg_match($namespaceRegex, $fileContents, $namespaceMatches)) {
                $this->classPathMap[$namespaceMatches[1] . '\\' . $justFileName] = $path;
            // This hack loads some GuzzleHttp functions
            } else if (PhpUtils::endsWith($path, 'functions.php')) {
                preg_match($functionRegex, $fileContents, $functionMatches);
                // Every other match will be the group with the function name
                for ($i = 1; $i < count($functionMatches); $i += 2) {
                    $this->classPathMap[$functionMatches[$i]] = $path;
                }
            // This loads naked classes without namespaces assuming file name and class name matches
            } else {
                $this->classPathMap[$justFileName] = $path;
            }
        }
        //$this->stopStopwatchLogMillis('ClassAutoloader: Class path map initialized from source directory in:');
        //$this->log(print_r($this->classPathMap, true));
        $this->cache(self::CACHE_KEY, $this->classPathMap);
    }

    private function init(): void
    {
        if (!empty($this->classPathMap)) {
            return;
        }
        //self::recreateTable();
        if (!$this->isCached(self::CACHE_KEY)) {
            $this->updateCachedClasspath();
        }
        //$this->startStopwatch();
        $this->classPathMap = $this->getCachedValue(self::CACHE_KEY);
        //$this->stopStopwatchLogMillis('ClassAutoloader: Class path map initialized from database in:');
    }

    public function autoload($className): void
    {
        $this->init();
        if (!class_exists($className, false) && !function_exists($className) && isset($this->classPathMap[$className])) {
            include_once($this->classPathMap[$className]);
        } else {
            $split = explode('\\', $className);
            $justClassName = end($split);
            if (!empty($justClassName) && !class_exists($justClassName, false) && !function_exists($justClassName) && isset($this->classPathMap[$justClassName])) {
                include_once($this->classPathMap[$justClassName]);
            }
        }
    }
}
