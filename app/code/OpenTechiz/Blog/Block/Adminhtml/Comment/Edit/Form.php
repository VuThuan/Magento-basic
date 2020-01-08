<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Newsletter Template Edit Form Block
 *
 * @authors     Magento Core Team <core@magentocommerce.com>
 */
namespace OpenTechiz\Blog\Block\Adminhtml\Comment\Edit;

/**
 * Adminhtml blog post edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \OpenTechiz\Blog\Model\Post\Source\Status
     */
    protected $_commentPost;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \OpenTechiz\Blog\Model\Post\Source\Status $_commentPost, 
        array $data = []
    ) {
        $this->_commentPost = $_commentPost;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \OpenTechiz\Blog\Model\Comment $model */
        $model = $this->_coreRegistry->registry('blog_comment');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $form->setHtmlIdPrefix('comment_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getCommentID()) {
            $fieldset->addField('comment_id', 'hidden', ['name' => 'comment_id']);
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField('customer_id', 'hidden', ['name' => 'customer_id']);
        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => [ '2' => __('Pending'), '1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getCommentID()) {
            $model->setData('is_active', '0');
            $model->setData('customer_id', '1');
            
            $fieldset->addField(
                'post_id',
                'select',
                [
                    'label' => __('Post'),
                    'title' => __('Post'),
                    'name'  => 'post_id',
                    'required' => true,
                    'style' => 'width: 300px',
                    'values' => $this->_commentPost->toOptionArray()
                ]
            );
        }
        $fieldset->addField(
            'comment',
            'editor',
            [
                'name' => 'comment',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:24em',
                'required' => true
            ]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}