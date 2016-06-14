<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/14
 * Time: 09:10
 */

namespace cdcchen\yii\alidayu;


use cdcchen\alidayu\SmsQueryClient;
use cdcchen\alidayu\SmsSendClient;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Client
 * @package cdcchen\yii\alidayu
 */
class Client extends Component
{
    /**
     * @var
     */
    public $appKey;
    /**
     * @var
     */
    public $appSecret;
    /**
     * @var string
     */
    public $format = 'json';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->appKey) || empty($this->appSecret)) {
            throw new InvalidConfigException('Property appKey|appSecret is required');
        }

        if ($this->format && !in_array($this->format, ['json', 'xml'])) {
            throw new InvalidConfigException('Property format can be only xml or json.');
        }
    }

    /**
     * @param string|array $receiveNumber
     * @param string $freeSignName
     * @param string $templateCode
     * @param null|array|string $params
     * @param null|string $extend
     * @return bool|\cdcchen\alidayu\Error|\cdcchen\alidayu\Response
     */
    public function sendSms($receiveNumber, $freeSignName, $templateCode, $params = null, $extend = null)
    {
        $client = $this->createSmsSendClient()
                       ->setReceiveNumber($receiveNumber)
                       ->setSmsFreeSignName($freeSignName)
                       ->setSmsTemplateCode($templateCode);

        if ($params) {
            $client->setSmsParams($params);
        }
        if ($extend) {
            $client->setExtend($extend);
        }
        return $client->execute();
    }

    /**
     * @param string $receiveNumber
     * @param string $date
     * @param int $currentPage
     * @param int $pageSize
     * @param null|string $bizId
     * @return bool|\cdcchen\alidayu\Error|\cdcchen\alidayu\Response
     */
    public function querySms($receiveNumber, $date, $currentPage = 1, $pageSize = 20, $bizId = null)
    {
        $client = $this->createSmsQueryClient()
                       ->setReceiveNumber($receiveNumber)
                       ->setQueryDate($date)
                       ->setCurrentPage($currentPage)
                       ->setPageSize($pageSize);

        if ($bizId) {
            $client->setBizId($bizId);
        }

        return $client->execute();

    }

    /**
     * @return SmsSendClient
     */
    public function createSmsSendClient()
    {
        $client = new SmsSendClient($this->appKey, $this->appSecret);
        if ($this->format) {
            $client->setFormat($this->format);
        }

        return $client;
    }

    /**
     * @return SmsQueryClient
     */
    public function createSmsQueryClient()
    {
        $client = new SmsQueryClient($this->appKey, $this->appSecret);
        if ($this->format) {
            $client->setFormat($this->format);
        }

        return $client;
    }
}