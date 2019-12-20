<?php

namespace OpenTechiz\Blog\Model\Comment\Source;

use Magento\Framework\Data\OptionSourceInterface;
use OpenTechiz\Blog\Model\Comment;

class IsActive implements OptionSourceInterface
{
    protected $comment;
    
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function toOptionArray()
    {
        $option[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->comment->getAvailableStatuses();
        foreach($availableOptions as $key => $value){
            $option[] = [
                'label' => $value,
                'value' => $key
            ];
        }
        return $option;
    }
}