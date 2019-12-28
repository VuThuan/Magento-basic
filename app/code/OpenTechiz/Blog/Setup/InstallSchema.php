<?php

namespace OpenTechiz\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table opentechiz_blog_post
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable("opentechiz_blog_post"))
            ->addColumn(
                'post_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Post_ID'
            )
            ->addColumn('url_key', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Blog Title')
            ->addColumn('content', Table::TYPE_TEXT, '2M', [], 'Blog Content')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Post Active?')
            ->addColumn('creation_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], 'Creation Time')
            ->addColumn('update_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE], 'Update Time')
            ->addIndex($installer->getIdxName('blog_post', ['url_key']), ['url_key'])
            ->setComment('Blog Posts');
        $installer->getConnection()->createTable($table);

        /**
         * Create table opentechiz_blog_comment
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('opentechiz_blog_comment'))
            ->addColumn('comment_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Comment ID')
            ->addColumn('comment', Table::TYPE_TEXT, '2M', ['nullable' => false], 'Comment')
            ->addColumn('post_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Blog Post ID')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Comment Active')
            ->addColumn('customer_id', Table::TYPE_INTEGER, 10, ['unsigned' => true, 'nullable' => false, 'default' => false], 'Customer ID')
            ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], 'Created at')
            ->addForeignKey(
                $installer->getFkName('opentechiz_blog_comment', 'post_id', $installer->getTable('opentechiz_blog_post'), 'post_id'),
                 'post_id',
                $installer->getTable('opentechiz_blog_post'),
                'post_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('opentechiz_blog_comment', 'customer_id', $installer->getTable('customer_entity'), 'entity_id'),
                 'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('opentechiz_blog_comment'),
                    ['comment'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['comment'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )->setComment('Comment Post Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}