<?php

namespace OpenTechiz\Blog\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;
use Magento\Framework\App\ObjectManager;

class CommentActions extends Column
{
    /** Url part */
    const BLOG_URL_PATH_EDIT   =   'blog/comment/edit';
    const BLOG_URL_PATH_DELETE =   'blog/comment/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /** @var string */
    private $editUrl;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param ContexInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context, 
        UiComponentFactory $uiComponentFactory, 
        UrlInterface $urlBuilder, 
        array $components = [],
        array $data = [],
        $editUrl = self::BLOG_URL_PATH_EDIT
    ){
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * 
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['comment_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['comment_id' => $item['comment_id']]),
                        'label' => __('Edit')
                    ];
                    $title = $this->getEscaper()->escapeHtml($item['comment']);
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::BLOG_URL_PATH_DELETE, ['comment_id' => $item['comment_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'comment' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title)
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get instance of escaper
     *
     * @return Escaper
     * @deprecated 101.0.7
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}