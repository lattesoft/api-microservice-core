<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 5:21 AM
 */

namespace Finiz\Notification;

use KS\Line\LineNotify;
use Finiz\Configurations\FinizConstant;

class LineNotification
{

    private $tokenId;

    public function __construct($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    public static function sendAlertMessage($text)
    {
        $ln = new LineNotify(config('web.token.line_monitor'));
        $ln->send($text);
    }

    /**
     *
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     *
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    public function sendTextMessage($text)
    {
        $ln = new LineNotify($this->tokenId);
        $ln->send($text);
    }
}