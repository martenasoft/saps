<?php

namespace App\Tests\Feedback;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\FeedbackRepository;
use App\Repository\PageRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class PatchFeedbackTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    public function testSuccessWithSameEmailAndSubject()
    {
        $client = self::createClient();
        $container = self::getContainer();
        $feedbackItem = GetFeedbackTest::initFeedbacks($container, 1)[0];
        $newText = 'patched text';

        $response = $client->request('PATCH', $_SERVER['HTTP_HOST'] . '/feedback/'.$feedbackItem->getId(), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => [
                'fromEmail' => $feedbackItem->getFromEmail(),
                'subject' => $feedbackItem->getSubject(),
                'text' => $newText
            ],
        ]);

        $response->toArray(false);
        $this->assertResponseStatusCodeSame(200);
        $inDb = $container->get(FeedbackRepository::class)->findOneByText($newText);
        $this->assertNotEmpty($inDb);
    }
}
