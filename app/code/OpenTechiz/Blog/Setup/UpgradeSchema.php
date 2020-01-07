<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace OpenTechiz\Blog\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * UpgradeSchema mock class
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        //Create table and check, that Magento can`t delete it
        if (version_compare($context->getVersion(), '2.0.2') < 0) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('opentechiz_blog_comment_approval_notification')
            )->addColumn(
                'notification_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Notification ID'
            )->addColumn(
                'content',
                Table::TYPE_TEXT,
                '2M',
                [],
                'Content of Notification'
            )->addColumn(
                'post_id',
                Table::TYPE_SMALLINT,
                null, ['nullable' => false], 'Blog Post ID'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                10, ['unsigned' => true, 'nullable' => false, 'default' => false], 'Customer ID'
            )->addColumn(
                'comment_id',
                Table::TYPE_SMALLINT,
                null, ['nullable' => false], 'Comment ID'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP, 
                null, ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], 'Created at'
            )->addForeignKey(
                $installer->getFkName('opentechiz_blog_comment_approval_notification', 'post_id', $installer->getTable('opentechiz_blog_post'), 'post_id'),
                 'post_id',
                $installer->getTable('opentechiz_blog_post'),
                'post_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('opentechiz_blog_comment_approval_notification', 'customer_id', $installer->getTable('customer_entity'), 'entity_id'),
                 'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('opentechiz_blog_comment_approval_notification', 'comment_id', $installer->getTable('opentechiz_blog_comment'), 'comment_id'),
                 'comment_id',
                $installer->getTable('opentechiz_blog_comment'),
                'comment_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Comment approval notification'
            );
            $setup->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
