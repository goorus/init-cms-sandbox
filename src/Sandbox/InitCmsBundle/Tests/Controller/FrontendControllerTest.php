<?php

namespace Sandbox\InitCmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue($crawler->filter('h3:contains("Demo Sailing Club")')->count() == 1, 'Its the demo sailing club');
        $this->assertTrue($crawler->filter('.header ul li.active a:contains("English")')->count() == 1, 'English');

        $this->assertTrue($crawler->filter('.hero-unit p:contains("The locale of this page is en_US")')->count() == 1, 'Language is English');
        $this->assertRegExp('#<h3>Demo Sailing Club</h3>#', $client->getResponse()->getContent(), 'its the demo sailing club');

    }

    public function testLanguageSwitch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('a:contains("Deutsch")')->count() == 1, 'there is a link for deutsch');

        $link = $crawler->selectLink('Deutsch')->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirect('/'), 'language switch does a redirect');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');
        $this->assertTrue($crawler->filter('.hero-unit p:contains("The locale of this page is de_CH")')->count() == 1, 'Language is switched');
        $this->assertTrue($crawler->filter('.header ul li.active a:contains("Deutsch")')->count() == 1, 'active is german');
        $this->assertTrue($crawler->filter('.header ul li.active a:contains("English")')->count() == 0, 'english is not active');
    }

}