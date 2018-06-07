<?php
namespace Nekonomokochan\Tests\Logger;

use Nekonomokochan\PhpJsonLogger\LoggerBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class PhpJsonLoggerTest
 *
 * @package Nekonomokochan\Tests
 */
class LoggerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // Delete the log file to assert the log file
        $defaultFile = '/tmp/php-json-logger-' . date('Y-m-d') . '.log';
        if (file_exists($defaultFile)) {
            unlink($defaultFile);
        }
    }

    /**
     * @test
     */
    public function outputUserAgent()
    {
        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36';

        $context = [
            'name' => 'keitakn',
        ];

        $loggerBuilder = new LoggerBuilder();
        $logger = $loggerBuilder->build();
        $logger->info('testOutputUserAgent', $context);

        unset($_SERVER['HTTP_USER_AGENT']);

        $resultJson = file_get_contents('/tmp/php-json-logger-' . date('Y-m-d') . '.log');
        $resultArray = json_decode($resultJson, true);

        echo "\n ---- Output Log Begin ---- \n";
        echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n ---- Output Log End   ---- \n";

        $expectedLog = [
            'log_level'         => 'INFO',
            'message'           => 'testOutputUserAgent',
            'trace_id'          => $logger->getTraceId(),
            'file'              => __FILE__,
            'line'              => 39,
            'context'           => $context,
            'remote_ip_address' => '127.0.0.1',
            'user_agent'        => $userAgent,
            'datetime'          => $resultArray['datetime'],
            'timezone'          => date_default_timezone_get(),
            'process_time'      => $resultArray['process_time'],
        ];

        $this->assertSame('PhpJsonLogger', $logger->getMonologInstance()->getName());
        $this->assertSame($expectedLog, $resultArray);
    }

    /**
     * @test
     */
    public function outputRemoteIpAddress()
    {
        $remoteIpAddress = '192.168.10.20';
        $_SERVER['REMOTE_ADDR'] = $remoteIpAddress;

        $context = [
            'name' => 'keitakn',
        ];

        $loggerBuilder = new LoggerBuilder();
        $logger = $loggerBuilder->build();
        $logger->info('testOutputRemoteIpAddress', $context);

        unset($_SERVER['REMOTE_ADDR']);

        $resultJson = file_get_contents('/tmp/php-json-logger-' . date('Y-m-d') . '.log');
        $resultArray = json_decode($resultJson, true);

        echo "\n ---- Output Log Begin ---- \n";
        echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n ---- Output Log End   ---- \n";

        $expectedLog = [
            'log_level'         => 'INFO',
            'message'           => 'testOutputRemoteIpAddress',
            'trace_id'          => $logger->getTraceId(),
            'file'              => __FILE__,
            'line'              => 82,
            'context'           => $context,
            'remote_ip_address' => $remoteIpAddress,
            'user_agent'        => 'unknown',
            'datetime'          => $resultArray['datetime'],
            'timezone'          => date_default_timezone_get(),
            'process_time'      => $resultArray['process_time'],
        ];

        $this->assertSame('PhpJsonLogger', $logger->getMonologInstance()->getName());
        $this->assertSame($expectedLog, $resultArray);
    }

    /**
     * @test
     */
    public function setTraceIdIsOutput()
    {
        $context = [
            'name' => 'keitakn',
        ];

        $loggerBuilder = new LoggerBuilder();
        $loggerBuilder->setTraceId('MyTraceID');
        $logger = $loggerBuilder->build();
        $logger->info('testSetTraceIdIsOutput', $context);

        $resultJson = file_get_contents('/tmp/php-json-logger-' . date('Y-m-d') . '.log');
        $resultArray = json_decode($resultJson, true);

        echo "\n ---- Output Log Begin ---- \n";
        echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n ---- Output Log End   ---- \n";

        $expectedLog = [
            'log_level'         => 'INFO',
            'message'           => 'testSetTraceIdIsOutput',
            'trace_id'          => 'MyTraceID',
            'file'              => __FILE__,
            'line'              => 123,
            'context'           => $context,
            'remote_ip_address' => '127.0.0.1',
            'user_agent'        => 'unknown',
            'datetime'          => $resultArray['datetime'],
            'timezone'          => date_default_timezone_get(),
            'process_time'      => $resultArray['process_time'],
        ];

        $this->assertSame('PhpJsonLogger', $logger->getMonologInstance()->getName());
        $this->assertSame('MyTraceID', $logger->getTraceId());
        $this->assertSame($expectedLog, $resultArray);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function setLogFileName()
    {
        $fileName = '/tmp/test-php-json-logger.log';
        $outputLogFile = '/tmp/test-php-json-logger-' . date('Y-m-d') . '.log';
        if (file_exists($outputLogFile)) {
            unlink($outputLogFile);
        }

        $context = [
            'cat'    => '🐱',
            'dog'    => '🐶',
            'rabbit' => '🐰',
        ];

        $loggerBuilder = new LoggerBuilder();
        $loggerBuilder->setFileName($fileName);
        $logger = $loggerBuilder->build();
        $logger->info('testSetLogFileName', $context);

        $resultJson = file_get_contents($outputLogFile);
        $resultArray = json_decode($resultJson, true);

        echo "\n ---- Output Log Begin ---- \n";
        echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n ---- Output Log End   ---- \n";

        $expectedLog = [
            'log_level'         => 'INFO',
            'message'           => 'testSetLogFileName',
            'trace_id'          => $logger->getTraceId(),
            'file'              => __FILE__,
            'line'              => 172,
            'context'           => $context,
            'remote_ip_address' => '127.0.0.1',
            'user_agent'        => 'unknown',
            'datetime'          => $resultArray['datetime'],
            'timezone'          => date_default_timezone_get(),
            'process_time'      => $resultArray['process_time'],
        ];

        $this->assertSame('PhpJsonLogger', $logger->getMonologInstance()->getName());
        $this->assertSame(
            $fileName,
            $logger->getLogFileName()
        );
        $this->assertSame($expectedLog, $resultArray);
    }

    /**
     * @test
     */
    public function setLogLevel()
    {
        $context = [
            'cat'    => '🐱',
            'dog'    => '🐶',
            'rabbit' => '🐰',
        ];

        $loggerBuilder = new LoggerBuilder();
        $loggerBuilder->setLogLevel(LoggerBuilder::CRITICAL);
        $logger = $loggerBuilder->build();
        $logger->info('testSetLogLevel', $context);

        $this->assertFalse(
            file_exists('/tmp/php-json-logger-' . date('Y-m-d') . '.log')
        );

        $this->assertSame('PhpJsonLogger', $logger->getMonologInstance()->getName());
        $this->assertSame(500, $logger->getLogLevel());
    }
}
