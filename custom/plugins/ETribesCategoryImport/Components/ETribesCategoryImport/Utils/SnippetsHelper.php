<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ETribesCategoryImport\Components\ETribesCategoryImport\Utils;

class SnippetsHelper
{
    /**
     * @param string $namespace
     *
     * @return \Enlight_Components_Snippet_Namespace
     */
    public static function getNamespace($namespace = 'e_tribes_category_import/main')
    {
        return Shopware()->Snippets()->getNamespace($namespace);
    }
}
