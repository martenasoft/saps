<?php

namespace App\Tests\Feedback;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\FeedbackRepository;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
class CreateFeedbackTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testErrorEmptyEmailAndSubject(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        unset($data['fromEmail']);
        unset($data['subject']);

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
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
        $this->assertArrayHasKey(1, $result['violations']);


        $this->assertEquals($result['violations'][0]['propertyPath'], 'fromEmail');
        $this->assertEquals($result['violations'][0]['message'], 'This value should not be blank.');

        $this->assertEquals($result['violations'][1]['propertyPath'], 'subject');
        $this->assertEquals($result['violations'][1]['message'], 'This value should not be blank.');
    }
    public function testErrorWrongEmail(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        $data['fromEmail'] = 'new_wrong_email';


        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
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
        $this->assertEquals($result['violations'][0]['message'], 'This value is not a valid email address.');
    }
    public function testErrorEmailAndSubjectExists(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];

        $data['fromEmail'] = $feedbackItem->getFromEmail();
        $data['subject'] = $feedbackItem->getSubject();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
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
    public function testSuccessTheSameEmailAndDifferentSubject(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];

        $data['fromEmail'] = $feedbackItem->getFromEmail();
        $data['subject'] = 'new subject';

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(FeedbackRepository::class)->findOneBySubject($data['subject']);
        $this->assertNotEmpty($inDb);
    }
    public function testSuccessTheDifferentEmailAndSameSubject(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];

        $data['fromEmail'] = 'new@mail.com';
        $data['subject'] = $feedbackItem->getSubject();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(FeedbackRepository::class)->findOneByFromEmail($data['fromEmail'] );
        $this->assertNotEmpty($inDb);
    }
    public function testSuccess(): void
    {
        $client = self::createClient();
        $data = self::getFeedbackPageData();
        $container = self::getContainer();

        $response = $client->request('POST', $_SERVER['HTTP_HOST'] . '/feedback', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $data,
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(201);
        $inDb = $container->get(FeedbackRepository::class)->findOneByFromEmail($data['fromEmail'] );
        $this->assertNotEmpty($inDb);
    }
    public static function getFeedbackPageData(): array
    {
        return [
            "fromEmail" => "new@mail.com",
            "subject" => "new subject",
            "text" => "new text",
            "status" => 0,
            "createdAt" => "2024-04-13T10:41:18.151Z",
            "updatedAt" => "2024-04-13T10:41:18.151Z"
        ];
    }
}
