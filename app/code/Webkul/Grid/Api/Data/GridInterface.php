<?php

namespace Webkul\Grid\Api\Data;

interface GridInterface 
{
    const ENTITY_ID         =   'entity_id';
    const TITLE           =   'title';
    const CONTENT         =   'content';
    const CREATED_AT   =   'created_at';
    const UPDATE_TIME     =   'update_time';
    const IS_ACTIVE       =   'is_active';
    const PUBLISH_DATE    =   'publish_date';

    public function getEntityId();
    public function getTitle();
    public function getContent();
    public function getCreatedAt();
    public function getUpdateTime();
    public function getIsActive();
    public function getPublishDate();

    public function setPublishDate($publishDate);
    public function setEntityId($id);
    public function setTitle($title);
    public function setContent($content);
    public function setCreatedAt($creationTime);
    public function setUpdateTime($updateTime);
    public function setIsActive($isActive);
}