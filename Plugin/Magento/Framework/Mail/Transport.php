<?php

namespace Ffm\SmtpConfigurator\Plugin\Magento\Framework\Mail;

use Zend\Mail\Message;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Transport extends \Zend\Mail\Transport\Smtp
{
    /**
     * @var \Ffm\SmtpConfigurator\Helper\Data
     */
    private $helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    protected $returnPathValue;
    protected $isSetReturnPath;

    /**
     * Transport constructor.
     * @param \Ffm\SmtpConfigurator\Helper\Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Ffm\SmtpConfigurator\Helper\Data $helper,
        ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->helper = $helper;
        $this->logger = $logger;

        $this->isSetReturnPath = (int) $scopeConfig->getValue(
            \Magento\Email\Model\Transport::XML_PATH_SENDING_SET_RETURN_PATH,
            ScopeInterface::SCOPE_STORE
        );
        $this->returnPathValue = $scopeConfig->getValue(
            \Magento\Email\Model\Transport::XML_PATH_SENDING_RETURN_PATH_EMAIL,
            ScopeInterface::SCOPE_STORE
        );


        $smtpHost = $helper->getConfigSmtpHost();
        $smtpProtocol = $helper->getConfigSmtpProtocol();

        $smtpConf = [
            'auth' => $helper->getConfigSmtpAuthentication(),
            'username' => $helper->getConfigSmtpUsername(),
            'password' => $helper->getConfigSmtpPassword()
        ];

        if ($smtpProtocol === \Ffm\SmtpConfigurator\Model\Config\Source\Protocol::PROTOCOL_SSL ||
            $smtpProtocol === \Ffm\SmtpConfigurator\Model\Config\Source\Protocol::PROTOCOL_TLS
        ) {
            $smtpConf['ssl'] = $smtpProtocol;
        }

        $smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtpOptions->setHost($smtpHost)
            ->setConnectionClass($helper->getConfigSmtpAuthentication())
            ->setName($smtpHost)
            ->setPort($helper->getConfigSmtpPort())
            ->setConnectionConfig($smtpConf)
        ;

        parent::__construct($smtpOptions);
    }

    /**
     * @param \Magento\Framework\Mail\TransportInterface $subject
     * @param \Closure $proceed
     */
    public function aroundSendMessage(\Magento\Framework\Mail\TransportInterface $subject, \Closure $proceed)
    {
        try {

            $zendMessage = Message::fromString($subject->getMessage()->getRawMessage())->setEncoding('utf-8');
            if (2 === $this->isSetReturnPath && $this->returnPathValue) {
                $zendMessage->setSender($this->returnPathValue);
            } elseif (1 === $this->isSetReturnPath && $zendMessage->getFrom()->count()) {
                $fromAddressList = $zendMessage->getFrom();
                $fromAddressList->rewind();
                $zendMessage->setSender($fromAddressList->current()->getEmail());
            }

            parent::send($zendMessage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }

}