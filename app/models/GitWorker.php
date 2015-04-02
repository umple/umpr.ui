<?php

require_once $GLOBALS['baseDir'] . '/vendor/autoload.php';

use GitWrapper\GitWrapper;
use GitWrapper\Event\GitLoggerListener;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


/**
 * Class GitWorker incrementally runs pulls against the data repository.
 */
class GitWorker extends Thread {

  private $url;
  private $path;

  private $repo;
  private $remote;
  private $branch;

  private $metaPath;

  private $log;

  private static $WAIT_TIME = 5 * 60;

  public function __construct($url, $path, $remote = "origin", $branch = "master") {
    $this->url = $url;
    $this->path = $path;
    $this->remote = $remote;
    $this->branch = $branch;

    $this->metaPath = $this->path . "/meta.json";

    $this->log = new Logger('git-worker');
    $this->log->pushHandler(new StreamHandler($GLOBALS['logDir'] . '/worker.log', Logger::DEBUG));

    // init git and logging:
    $wrapper = new GitWrapper();

    $gitLog = new Logger('git');
    $gitLog->pushHandler(new StreamHandler($GLOBALS['logDir'] . '/git.log', Logger::DEBUG));

    // Instantiate the listener, add the logger to it, and register it.
    $listener = new GitLoggerListener($gitLog);
    $wrapper->addLoggerListener($listener);

    if (!file_exists($this->path . "/.git")) {
      if (!file_exists($this->path)) {
        mkdir($this->path);
      }

      // the repository isn't around
      $this->repo = $wrapper->cloneRepository($this->url, $this->path);
    } else {
      $this->repo = $wrapper->workingCopy($this->path);
    }
  }

  // The last time the metafile was opened, default to 0 since it will never be zero

  /**
   * @internal
   */
  public function __pull() {
    $this->repo->pull($this->remote, $this->branch);
  }

  public function run() {
    require_once __DIR__ . '/../../vendor/autoload.php';
    if (function_exists('date_default_timezone_set')) date_default_timezone_set('America/New_York');

    $this->__pull();

    $base = new EventBase();

    $e = Event::timer($base, array($this, '__pull'));

    $e->addTimer(self::$WAIT_TIME);

    while (!$this->isTerminated()) {
      $base->loop(EventBase::LOOP_ONCE);
    }
  }
}
