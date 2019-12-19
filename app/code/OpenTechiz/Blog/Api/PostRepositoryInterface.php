<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Blog Posts CRUD interface.
 * @api
 * @since 100.0.2
 */
interface PostRepositoryInterface
{
    /**
     * Save Post.
     *
     * @param \OpenTechiz\Blog\Api\Data\PostInterface $post
     * @return \OpenTechiz\Blog\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\OpenTechiz\Blog\Api\Data\PostInterface $post);

    /**
     * Retrieve post.
     *
     * @param int $postId
     * @return \OpenTechiz\Blog\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($postId);
    
    /**
     * Delete post.
     *
     * @param \OpenTechiz\Blog\Api\Data\PostInterface $post
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\OpenTechiz\Blog\Api\Data\PostInterface $post);

    /**
     * Delete post by ID.
     *
     * @param int $postId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($postId);
}
