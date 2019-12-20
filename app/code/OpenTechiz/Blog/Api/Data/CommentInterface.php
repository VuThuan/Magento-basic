<?php

namespace OpenTechiz\Blog\Api\Data;

interface CommentInterface
{
    const COMMENT_ID = 'comment_id';
    const COMMENT = 'comment';
    const POST_ID = 'post_id';
    const IS_ACTIVE = 'is_active';
    const CUSTOMER_ID = 'customer_id';
    const CREATED_AT = 'created_at';

    public function getCommentId();
    public function getComment();
    public function getPostId();
    public function getIsActive();
    public function getCustomerId();
    public function getCreatedAt();

    public function setCommentId($commentId);
    public function setComment($comment);
    public function setPostId($postId);
    public function setIsActive($isActive);
    public function setCustomerId($customerId);
    public function setCreatedAt($createdTime);
}