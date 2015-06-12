<?php
/**
 * Static logger.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

use Doctrine\Common\Util\Debug;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Stream;

class Logger
{
    protected static $logger;
    protected static $writer;

    /**
     * Logs function parameters.
     */
    public static function log()
    {
        if (!self::$logger)
        {
            self::createLogger();
        }

        foreach(func_get_args() as $argument)
        {
            self::$logger->log(ZendLogger::DEBUG, Debug::dump($argument, 4, true, false));
        }
    }

    /**
     * Creates logger.
     */
    protected static function createLogger()
    {
        $dir = './build/logs/zend/'.date('Y') . '/' . date('m').'/';

        if (!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }

        self::$logger = new ZendLogger;
        self::$writer = new Stream($dir . date('d') .'.log');
        self::$logger->addWriter(self::$writer);
    }
}
