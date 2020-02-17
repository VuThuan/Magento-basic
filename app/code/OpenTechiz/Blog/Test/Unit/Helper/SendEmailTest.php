<?php

namespace OpenTechiz\Blog\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class SendEmailTest extends TestCase
{
    /**
     * @var ObjectManagerHelper
     */
    private $objectManagerHelper;

    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transportBuilderMock;

    /**
     * @var \OpenTechiz\Blog\Helper\SendEmail
     */
    private $sendEmailHelper;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transportBuilderMock = $this->getMockBuilder(
            \Magento\Framework\Mail\Template\TransportBuilder::class
        )->disableOriginalConstructor(
        )->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);

        $this->sendEmailHelper = $this->objectManagerHelper->getObject(
            \OpenTechiz\Blog\Helper\SendEmail::class, [
                'config' => $this->configMock,
                'transportBuilder' => $this->transportBuilderMock
        ]);
    }

    public function testSendEmail()
    {
        
    }
}