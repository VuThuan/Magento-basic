<?php

namespace OpenTechiz\Blog\Model\Comment\Source;

use Magento\Framework\Data\OptionSourceInterface;
use OpenTechiz\Blog\Model\Comment;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var OpenTechiz\Blog\Model\Comment
     */
    protected $comment;
    
    /**
     * Constructor
     *
     * @param OpenTechiz\Blog\Model\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->comment->getAvailableStatuses();
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