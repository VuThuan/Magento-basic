<?php

namespace OpenTechiz\Blog\Api\Data;

interface NotificationInterface 
{
    const NOTIFICATION_ID         =   'notification_id';
    const CONTENT                 =   'content';
    const POST_ID                 =   'post_id';
    const CUSTOMER_ID             =   'customer_id';
    const COMMENT_ID              =   'comment_id';
    const CREATED_AT              =   'created_at';

    public function getNotificationID();
    public function getContent();
    public function getPostID();
    public function getCustomerID();
    public function getCommentID();
    public function getCreatedAt();

    public function setNotificationID($id);
    public function setContent($content);
    public function setPostID($postID);
    public function setCustomerID($customerID);
    public function setCommentID($commentID);
    public function setCreatedAt($creationTime);
}