<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class EmailAjaxControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testEmail()
    {
        // Anonyme tester
        $this->client->xmlHttpRequest('GET', '/ajax/email', array('email' => ''));
        $this->assertFalse(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        // Authenticated tester
        $this->login();

        // Email vide.
        $email = '';
        $this->client->xmlHttpRequest('GET', '/ajax/email', array('email' => $email));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('null', $data['status']);

        // Email valide
        $email = 'qweasd@qwe.com';
        $this->client->xmlHttpRequest('GET', '/ajax/email', array('email' => $email));
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('success', $data['status']);

        // Email invalide
        $email = 'qweasd@qwecom';
        $this->client->xmlHttpRequest('GET', '/ajax/email', array('email' => $email));
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('error', $data['status']);

    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';
        $token = new UsernamePasswordToken('test', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}