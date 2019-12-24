<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Block\Adminhtml\Comment;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Init class
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'comment_id';
        $this->_controller = 'adminhtml_comment';
        $this->_blockGroup = 'OpenTechiz_Blog';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Comment'));
        $this->buttonList->remove('delete');
        $this->buttonList->add(
            'save_and_continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mane-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']
                    ]
                ]
            ],
            101
        );
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $item = $this->_coreRegistry->registry('blog_comment');
        if ($item->getCommentId()) {
            return __("Edit Comment :  '%1'", $this->escapeHtml($item->getComment()));
        } else {
            return __('New Comment');
        }
    }
}
