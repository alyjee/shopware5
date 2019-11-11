<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SwagImportExport\Tests\Unit\Setup\DefaultProfiles;

use PHPUnit\Framework\TestCase;
use Shopware\Setup\SwagImportExport\DefaultProfiles\ArticleProfile;
use Shopware\Setup\SwagImportExport\DefaultProfiles\ProfileMetaData;

class ArticleProfileTest extends TestCase
{
    use DefaultProfileTestCaseTrait;

    public function test_it_can_be_created()
    {
        $articleProfile = $this->createArticleProfile();

        $this->assertInstanceOf(ArticleProfile::class, $articleProfile);
        $this->assertInstanceOf(ProfileMetaData::class, $articleProfile);
        $this->assertInstanceOf(\JsonSerializable::class, $articleProfile);
    }

    public function test_it_should_return_valid_profile_tree()
    {
        $articleProfile = $this->createArticleProfile();

        $this->walkRecursive($articleProfile->jsonSerialize(), function ($node) {
            $this->assertArrayHasKey('id', $node, 'Current array: ' . print_r($node, true));
            $this->assertArrayHasKey('name', $node, 'Current array: ' . print_r($node, true));
            $this->assertArrayHasKey('type', $node, 'Current array: ' . print_r($node, true));
        });
    }

    /**
     * @return ArticleProfile
     */
    private function createArticleProfile()
    {
        return new ArticleProfile();
    }
}