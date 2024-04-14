<?php

namespace App\Tests\Menu;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Menu;
use App\Helper\StringHelper;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
class GetMenuCollectionTest extends ApiTestCase
{
    public function testEmptyList(): void
    {
        $client = self::createClient();
        $container = self::getContainer();
        self::cleareMenu($container);
        $result = self::get($client);

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 0);
        self::initMenu($container);
    }

    public function testList(): void
    {
        $client = self::createClient();
        $result = self::get($client);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 15);
        $this->assertEquals(count($result['hydra:member']), 15);

        for ($i = 1, $g = 0; $i <= 5; $i++) {
            for ($f = 1, $rgt = 6; $f <= 3; $f++, $g++, $rgt--) {

                $this->assertArrayHasKey($g, $result['hydra:member']);
                $this->assertArrayHasKey('lft', $result['hydra:member'][$g]);
                $this->assertArrayHasKey('rgt', $result['hydra:member'][$g]);
                $this->assertArrayHasKey('lvl', $result['hydra:member'][$g]);
                $this->assertArrayHasKey('tree', $result['hydra:member'][$g]);

                $this->assertEquals($result['hydra:member'][$g]['lft'], $f);
                $this->assertEquals($result['hydra:member'][$g]['rgt'], $rgt);
                $this->assertEquals($result['hydra:member'][$g]['lvl'], $f);
                $this->assertEquals($result['hydra:member'][$g]['tree'], $i);
            }
        }
    }
    public function testFindByFilter()
    {
        $client = self::createClient();
        $treeId = 1;
        $result = self::get($client, [
            'lft[lt]' => 3,
            'rgt[gt]' => 1,
            'tree' => $treeId
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('hydra:totalItems', $result);
        $this->assertEquals($result['hydra:totalItems'], 2);

        for ($i = 0, $rgt = 6; $i < 2; $i++, $rgt--) {
            $this->assertArrayHasKey($i, $result['hydra:member']);
            $this->assertArrayHasKey('lft', $result['hydra:member'][$i]);
            $this->assertArrayHasKey('rgt', $result['hydra:member'][$i]);
            $this->assertArrayHasKey('tree', $result['hydra:member'][$i]);

            $this->assertEquals($result['hydra:member'][$i]['lft'], $i+1);
            $this->assertEquals($result['hydra:member'][$i]['rgt'], $rgt);
            $this->assertEquals($result['hydra:member'][$i]['tree'], $treeId);
        }
    }

    public static function cleareMenu($container)
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManager->getConnection()->executeQuery("SET FOREIGN_KEY_CHECKS = 0");
        $entityManager->getConnection()->executeQuery("TRUNCATE TABLE menu");
        $entityManager->getConnection()->executeQuery("SET FOREIGN_KEY_CHECKS = 1");
    }

    public static function initMenu($container, int $maxTrees = 5, int $maxLevels = 3): array
    {
        $menuRepository = $container->get(MenuRepository::class);
        $result = [];

        for ($i = 1; $i <= $maxTrees; $i++) {
            $parent = null;
            for ($f = 1; $f <= $maxLevels; $f++) {

                $name = "Menu $i $f";
                $menu = self::getMenu($name);
                if (!empty($parent)) {
                    $parent = $menuRepository->find($parent->getId());
                }
                $menuRepository->create($menu, $parent);
                $result[] = $menu;
                $parent = $menu;

            }
        }
        return $result;
    }

    public static function getMenu(string $name): Menu
    {
        $now = new \DateTimeImmutable('now');
        $menu = new Menu();
        $menu
            ->setName($name)
            ->setStatus($menu->getDefaultStatus())
            ->setType($menu->getDefaultType())
            ->setCreatedAt($now)
            ->setSlug(StringHelper::slug($name));
        return $menu;
    }

    public static function get($client, array $params = []): array
    {
        $paramsStr = http_build_query($params);
        $response = $client->request('GET', $_SERVER['HTTP_HOST'] . '/menus' .(!empty($paramsStr) ? '?'.$paramsStr : ''), [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json'
            ]
        ]
        );
        $result = $response->toArray(false);
        return $result;
    }
}
