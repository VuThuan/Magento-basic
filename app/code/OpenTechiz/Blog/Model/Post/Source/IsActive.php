<?php

namespace OpenTechiz\Blog\Model\Post\Source;

use Magento\Framework\Data\OptionSourceInterface;
use OpenTechiz\Blog\Model\Post;

class IsActive implements OptionSourceInterface
{
    protected $post;
    
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->post->getAvailableStatuses();
        $option = [];
        foreach($availableOptions as $key => $value){
            $option[] = [
                'label' => $value,
                'value' => $key
            ];
        }
        return $option;
    }
}