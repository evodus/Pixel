<?php
/**
 * Copyright (c) 2018. Evodus.com
 * All right reserved
 */

namespace Evodus\Pixel\Block\Adminhtml;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends \Magento\Backend\Block\AbstractBlock implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Retrieve element HTML markup.
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element  = null;
        $html     = <<<HTML
<div style="background: #fafafa;padding: 5rem 2rem;font-size: 1.8rem;line-height: 3rem;">
<p style="text-align: center;font-weight: 600;">Follow and connect with us: </p>
<br /> 
Website: <a href="https://www.evodus.com" target="_blank">www.evodus.com</a><br /> 
Our Facebook: <a href="https://www.facebook.com/evodus" target="_blank">Facebook</a><br /> 
Our Twitter: <a href="https://twitter.com/EvodusStudio" target="_blank">Twitter</a><br />
Our email: <a href="mailto:hello@evodus.com">hello@evodus.com</a>.
</p>
</div>
HTML;
        return $html;
    }
}
