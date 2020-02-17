<?php 

namespace OpenTechiz\Blog\Test\Unit\Block;

use PHPUnit\Framework\TestCase;

class CommentListTest extends TestCase
{
    /**
     * @var \OpenTechiz\Blog\Model\Comment
     */
    protected $comment;

    /**
     * @var \OpenTechiz\Blog\Block\CommentList
     */
    protected $block;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(\OpenTechiz\Blog\Block\CommentList::class);
        $this->comment = $objectManager->getObject(\OpenTechiz\Blog\Model\Comment::class);
    }

    protected function tearDown()
    {
        $this->block = null;
    }

    public function testGetIdentities()
    {
        $this->assertEquals(
            [\OpenTechiz\Blog\Model\Comment::CACHE_POST_COMMENT_TAG . '_' ],
            $this->block->getIdentities()
        );
    }
}