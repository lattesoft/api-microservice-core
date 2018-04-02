<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/28/2018
 * Time: 3:12 AM
 */

namespace Lattesoft\ApiMicroserviceCore\Util;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Exception;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class ILogger
{
    const LOGGER_MODE_AWS = 'AWS';

    const ENABLE_LOG_YES = 'Y';
    const ENABLE_LOG_NO = 'N';

    protected $logId;
    protected $mode;

    /*
     * For AWS
     * */
    protected $client;
    protected $groupName = 'Finiz_API_Logs';
    protected $retentionDays = 30;
    protected $enableLog = 'Y';

    public function __construct($mode)
    {
        $this->enableLog = config('web.cloud.watch.aws.enable');
        if ($this->enableLog == ILogger::ENABLE_LOG_YES) {
            $this->logId = IFunction::generateRandomString(16);
            if ($mode == ILogger::LOGGER_MODE_AWS) {
                $this->mode = $mode;
                $this->groupName = config('web.cloud.watch.aws.group.name');
                $sdkParams = [
                    'region' => config('web.cloud.watch.aws.region'),
                    'version' => config('web.cloud.watch.aws.version'),
                    'credentials' => [
                        'key' => config('web.cloud.watch.aws.credentials.key'),
                        'secret' => config('web.cloud.watch.aws.credentials.secret')
                    ]
                ];
                $this->client = new CloudWatchLogsClient($sdkParams);
            } else {
                throw new Exception('ILogger mode not found');
            }
        }
    }

    function logDebug($subject, $arrData)
    {
        if ($this->enableLog == ILogger::ENABLE_LOG_YES) {
            $streamName = 'system_' . date('Y-m-d');
            $handler = new CloudWatch($this->client, $this->groupName, $streamName, $this->retentionDays, 10000);
            $handler->setFormatter(new LineFormatter(null, null, false, true));
            $log = new Logger('LOGGER');
            $log->pushHandler($handler);
            if (!is_array($arrData)) {
                $arrData = ['message' => $arrData];
            }
            $log->debug($subject . ' [' . $this->logId . ']', $arrData);
        }
    }


}