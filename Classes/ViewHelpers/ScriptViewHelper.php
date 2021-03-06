<?php
declare(strict_types = 1);

/*
 * This file is part of the package t3g/usercentrics.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\Usercentrics\ViewHelpers;

use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Usercentrics Viewhelper
 *
 * Examples
 * ========
 *
 * ::
 *
 *    <usercentrics:script dataProcessingService="Data Service" src="EXT:my_ext/Resources/Public/JavaScript/foo.js" />
 *    <usercentrics:script dataProcessingService="Data Service">
 *       alert('hello world');
 *    </usercentrics:script>
 */
class ScriptViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Asset\ScriptViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('dataServiceProcessor', 'string', 'the data processing service name as configured in Usercentrics', true);
        $this->registerArgument('dataProcessingService', 'string', 'the data processing service name as configured in Usercentrics', true);
    }

    public function render(): string
    {
        $dataProcessingService = $this->getDataProcessingService();
        $identifier = StringUtility::getUniqueId($dataProcessingService . '-');
        $attributes = $this->tag->getAttributes();
        $attributes['type'] = 'text/plain';
        $attributes['data-usercentrics'] = $dataProcessingService;
        $src = $this->tag->getAttribute('src');
        unset($attributes['src']);
        $options = [
            'priority' => $this->arguments['priority']
        ];
        if ($src !== null) {
            $this->assetCollector->addJavaScript($identifier, $src, $attributes, $options);
        } else {
            $content = (string)$this->renderChildren();
            if ($content !== '') {
                $this->assetCollector->addInlineJavaScript($identifier, $content, $attributes, $options);
            }
        }
        return '';
    }

    protected function getDataProcessingService(): string
    {
        if (isset($this->arguments['dataServiceProcessor'])) {
            trigger_error(
                'The argument "dataServiceProcessor" of ' . self::class . ' has been marked as deprecated. Use dataProcessingService instead.',
                E_USER_DEPRECATED
            );
            return $this->arguments['dataServiceProcessor'];
        }
        return $this->arguments['dataProcessingService'];
    }
}
