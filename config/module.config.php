<?php
namespace FacetedBrowse;

use Laminas\Router\Http;

return [
    'faceted_browse_facet_types' => [
        'factories' => [
            'value' => Service\FacetType\FacetTypeFactory::class,
            'by_class' => Service\FacetType\FacetTypeFactory::class,
            'by_template' => Service\FacetType\FacetTypeFactory::class,
            'by_item_set' => Service\FacetType\FacetTypeFactory::class,
            'full_text' => Service\FacetType\FacetTypeFactory::class,
        ],
    ],
    'faceted_browse_column_types' => [
        'factories' => [
            'title' => Service\ColumnType\ColumnTypeFactory::class,
            'value' => Service\ColumnType\ColumnTypeFactory::class,
            'resource_class' => Service\ColumnType\ColumnTypeFactory::class,
            'resource_template' => Service\ColumnType\ColumnTypeFactory::class,
            'id' => Service\ColumnType\ColumnTypeFactory::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => sprintf('%s/../language', __DIR__),
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            sprintf('%s/../view', __DIR__),
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'entity_manager' => [
        'mapping_classes_paths' => [
            sprintf('%s/../src/Entity', __DIR__),
        ],
        'proxy_paths' => [
            sprintf('%s/../data/doctrine-proxies', __DIR__),
        ],
    ],
    'service_manager' => [
        'factories' => [
            'FacetedBrowse\FacetTypeManager' => Service\FacetType\ManagerFactory::class,
            'FacetedBrowse\ColumnTypeManager' => Service\ColumnType\ManagerFactory::class,
        ],
    ],
    'api_adapters' => [
        'invokables' => [
            'faceted_browse_categories' => Api\Adapter\FacetedBrowseCategoryAdapter::class,
            'faceted_browse_pages' => Api\Adapter\FacetedBrowsePageAdapter::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
            'FacetedBrowse\Controller\SiteAdmin\Category' => Controller\SiteAdmin\CategoryController::class,
            'FacetedBrowse\Controller\SiteAdmin\Page' => Controller\SiteAdmin\PageController::class,
            'FacetedBrowse\Controller\Site\Page' => Controller\Site\PageController::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'facetedBrowse' => Service\ControllerPlugin\FacetedBrowseFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'facetedBrowse' => Service\ViewHelper\FacetedBrowseFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            'FacetedBrowse\Form\PageForm' => Service\Form\PageFormFactory::class,
            'FacetedBrowse\Form\CategoryForm' => Service\Form\CategoryFormFactory::class,
        ],
    ],
    'navigation_links' => [
        'invokables' => [
            'facetedBrowse' => Site\NavigationLink\FacetedBrowse::class,
        ],
    ],
    'js_translate_strings' => [
        'A facet must have a name.', // @translate
        'A column must have a name.', // @translate
        'A column must have a property.', // @translate
        'Error fetching browse markup.', // @translate
        'Error fetching facet markup.', // @translate
        'Error fetching category markup.', // @translate
    ],
    'navigation' => [
        'site' => [
            [
                'label' => 'Faceted Browse', // @translate
                'route' => 'admin/site/slug/faceted-browse',
                'controller' => 'category',
                'action' => 'browse',
                'useRouteMatch' => true,
                'pages' => [
                    [
                        'label' => 'Categories', // @translate
                        'route' => 'admin/site/slug/faceted-browse',
                        'controller' => 'category',
                        'action' => 'browse',
                        'useRouteMatch' => true,
                        'pages' => [
                            [
                                'route' => 'admin/site/slug/faceted-browse',
                                'controller' => 'category',
                                'visible' => false,
                            ],
                            [
                                'route' => 'admin/site/slug/faceted-browse/id',
                                'controller' => 'category',
                                'visible' => false,
                            ],
                        ],
                    ],
                    [
                        'label' => 'Pages', // @translate
                        'route' => 'admin/site/slug/faceted-browse',
                        'controller' => 'page',
                        'action' => 'browse',
                        'useRouteMatch' => true,
                        'pages' => [
                            [
                                'route' => 'admin/site/slug/faceted-browse',
                                'controller' => 'page',
                                'visible' => false,
                            ],
                            [
                                'route' => 'admin/site/slug/faceted-browse/id',
                                'controller' => 'page',
                                'visible' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'faceted-browse' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => '/faceted-browse/:page-id[/:action]',
                            'constraints' => [
                                'page-id' => '\d+',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'FacetedBrowse\Controller\Site',
                                'controller' => 'page',
                                'action' => 'page',
                            ],
                        ],
                    ],
                ],
            ],
            'admin' => [
                'child_routes' => [
                    'site' => [
                        'child_routes' => [
                            'slug' => [
                                'child_routes' => [
                                    'faceted-browse' => [
                                        'type' => Http\Segment::class,
                                        'options' => [
                                            'route' => '/faceted-browse/:controller/:action',
                                            'constraints' => [
                                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'id' => '\d+',
                                            ],
                                            'defaults' => [
                                                '__NAMESPACE__' => 'FacetedBrowse\Controller\SiteAdmin',
                                                'controller' => 'category',
                                                'action' => 'browse',
                                            ],
                                        ],
                                        'may_terminate' => true,
                                        'child_routes' => [
                                            'id' => [
                                                'type' => Http\Segment::class,
                                                'options' => [
                                                    'route' => '/:id',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
