<?php

namespace App\Tests\Feedback;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\FeedbackRepository;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class PutFeedbackTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testErrorWithSameEmailAndSubject()
    {
        $client = self::createClient();
        $data = CreateFeedbackTest::getFeedbackPageData();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];

        $data['fromEmail'] = $feedbackItem->getFromEmail();
        $data['subject'] = $feedbackItem->getSubject();


        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/feedback/' . $feedbackItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $result = $response->toArray(false);
        $this->assertResponseStatusCodeSame(422);
        $this->assertArrayHasKey('violations', $result);
        $this->assertNotEmpty($result['violations']);
        $this->assertArrayHasKey(0, $result['violations']);

        $this->assertEquals($result['violations'][0]['propertyPath'], 'fromEmail');
        $this->assertEquals($result['violations'][0]['message'], 'There is already a massage already exists');
    }
    public function testSuccess()
    {
        $client = self::createClient();
        $data = CreateFeedbackTest::getFeedbackPageData();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];

        $data['fromEmail'] = 'new@user.com';
        $data['subject'] = 'new subject';

        $response = $client->request('PUT', $_SERVER['HTTP_HOST'] . '/feedback/' . $feedbackItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $inDb = $container->get(FeedbackRepository::class)->findOneByFromEmail($data['fromEmail']);
        $this->assertNotEmpty($inDb);
    }
}
