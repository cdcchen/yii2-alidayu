<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/14
 * Time: 09:10
 */

namespace cdcchen\yii\alidayu;


use cdcchen\alidayu\Client as DayuClient;
use cdcchen\alidayu\SmsQueryRequest;
use cdcchen\alidayu\SmsSendRequest;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Client
 * @package cdcchen\yii\alidayu
 */
class Client extends Component
{
    /**
     * @var string
     */
    public $appKey;
    /**
     * @var string
     */
    public $appSecret;
    /**
     * @var string
     */
    public $format = 'json';

    /**
     * @var string|null
     */
    public $restUrl;

    private $_client;

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
     * @return DayuClient
     */
    public function getDayuClient()
    {
        if ($this->_client === null) {
            $this->_client = new DayuClient($this->appKey, $this->appSecret);
            if ($this->format) {
                $this->_client->setFormat($this->format);
            }

            if ($this->restUrl) {
                $this->_client->setRestUrl($this->restUrl);
            }
        }

        return $this->_client;
    }

    /**
     * @param string|array $receiveNumber
     * @param string $freeSignName
     * @param string $templateCode
     * @param null|array|string $params
     * @param null|string $extend
     * @return bool|\cdcchen\alidayu\ErrorResponse|\cdcchen\alidayu\SuccessResponse
     */
    public function sendSms($receiveNumber, $freeSignName, $templateCode, $params = null, $extend = null)
    {
        $client = $this->getDayuClient();
        $request = new SmsSendRequest();
        $request->setReceiveNumber($receiveNumber)
                ->setSmsFreeSignName($freeSignName)
                ->setSmsTemplateCode($templateCode);

        if ($params) {
            $request->setSmsParams($params);
        }
        if ($extend) {
            $request->setExtend($extend);
        }
        return $client->execute($request);
    }

    /**
     * @param string $receiveNumber
     * @param string $date
     * @param int $currentPage
     * @param int $pageSize
     * @param null|string $bizId
     * @return bool|\cdcchen\alidayu\ErrorResponse|\cdcchen\alidayu\SuccessResponse
     */
    public function querySms($receiveNumber, $date, $currentPage = 1, $pageSize = 20, $bizId = null)
    {
        $client = $this->getDayuClient();
        $request = new SmsQueryRequest();
        $request->setReceiveNumber($receiveNumber)
                ->setQueryDate($date)
                ->setCurrentPage($currentPage)
                ->setPageSize($pageSize);

        if ($bizId) {
            $request->setBizId($bizId);
        }

        return $client->execute($request);
    }
}