<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Tests\TestCase;
use Mockery as m;

/**
 * If anybody has any clues here just what this is supposed to be testing and how,
 * then please dive in.
 */

class ServerNotifyRequestTest extends TestCase
{
    public function testServerNotifyResponseSuccess()
    {
        parent::setUp();

        $this->request = new ServerNotifyRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'Status' => 'OK',
                'TxAuthNo' => '4255',
                'VPSTxId' => '{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => '1234',
                'DeclineCode' => '00',
                'ExpiryDate' => '0722',
                'BankAuthCode' => '999777',
            )
        );

        //$this->getMockRequest()->shouldReceive('getTransactionReference')->once()->andReturn('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}');

        //$this->assertSame('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}', $this->request->getTransactionReference());
        //$this->assertNull($this->request->getMessage());

        //$this->assertSame('0707', $this->request->getExpiryDate());

        // FIXME: disabled until I work out how yo initialise a server request (notify)
        // object with data.

        $this->assertSame('DISABLED', 'DISABLED');
    }

    public function DISABLED_testServerNotifyResponseFailure()
    {
        $response = new ServerNotifyResponse($this->getMockRequest(), array('Status' => 'INVALID'));
        $this->assertFalse($response->isSuccessful());

        $this->assertFalse($response->isRedirect());

        // The mocked request does not have getTransactionReference() or any of the other
        // methods that this response uses, e.g. isValid() and getTransactionStatus()
        // To test this thoroughly, we would use the non-mocked ServerNotifyRequest.

        //$this->assertNull($response->getTransactionReference());
        //$this->assertSame('FAILED', $response->getTransactionStatus());

        $this->assertNull($response->getMessage());
    }

    public function DISABLED_testConfirm()
    {
        $response = m::mock('\Omnipay\SagePay\Message\ServerNotifyResponse', array('isValid' => 1))->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('OK', 'https://www.example.com/', 'detail');

        $response->confirm('https://www.example.com/', 'detail');
        //$response->sendResponse('OK', 'https://www.example.com/', 'detail');
    }

    public function DISABLED_testError()
    {
        $response = m::mock('\Omnipay\SagePay\Message\ServerNotifyResponse', array('isValid' => 1))->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('ERROR', 'https://www.example.com/', 'detail');

        $response->error('https://www.example.com/', 'detail');
        //$response->sendResponse('ERROR', 'https://www.example.com/', 'detail');
    }

    public function DISABLED_testInvalid()
    {
        $response = m::mock('\Omnipay\SagePay\Message\ServerNotifyResponse', array('isValid' => 0))->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('INVALID', 'https://www.example.com/', 'detail');

        $response->invalid('https://www.example.com/', 'detail');
        //$response->sendResponse('INVALID', 'https://www.example.com/', 'detail');
    }

    public function DISABLED_testSendResponse()
    {
        $response = m::mock('\Omnipay\SagePay\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('exitWith')->once()->with("Status=FOO\r\nRedirectUrl=https://www.example.com/");

        $response->sendResponse('FOO', 'https://www.example.com/');
    }

    public function DISABLED_testSendResponseDetail()
    {
        $response = m::mock('\Omnipay\SagePay\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('exitWith')->once()->with("Status=FOO\r\nRedirectUrl=https://www.example.com/\r\nStatusDetail=Bar");

        $response->sendResponse('FOO', 'https://www.example.com/', 'Bar');
    }

    public function DISABLED_testServerNotifyResponseSuccess()
    {
        $VPSTxId = '{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}';

        $transactionReference = '{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"'.$VPSTxId.'","VendorTxCode":"438791"}';

        $response = new ServerNotifyResponse(
            $this->getMockRequest(),
            array(
                'Status' => 'OK',
                'TxAuthNo' => '4255',
                'VendorTxCode' => '438791',
                'VPSTxId' => $VPSTxId,
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => '1234',
                'DeclineCode' => '00',
                'ExpiryDate' => '0722',
                'BankAuthCode' => '999777',
                'VPSSignature' => '54b1939f699b6d71c756b701d96baa06',
                // Parameter values (for calculating the signature).
                'vendor' => 'academe',
                'securityKey' => 'JEUPDN1N7E',
            )
        );

        //$this->getMockRequest()->shouldReceive('getTransactionReference')->once()->andReturn($transactionReference);

        $this->assertSame('OK', $response->getCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($transactionReference, $response->getTransactionReference());
        $this->assertNull($response->getMessage());

        $this->assertSame('0722', $response->getExpiryDate());
        $this->assertSame('2022-07', $response->getExpiryDate('Y-m'));
        $this->assertSame(7, $response->getExpiryMonth());
        $this->assertSame(2022, $response->getExpiryYear());
        $this->assertSame('1234', $response->getNumberLastFour());
        $this->assertSame('1234', $response->getLast4Digits());

        $this->assertSame('completed', $response->getTransactionStatus());
    }
}
