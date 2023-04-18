<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (https://www.boxbilling.org)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */


namespace Box\Mod\Extension\Api;

/**
 * Extensions
 */
class Client extends \Api_Abstract
{

    /**
     * Retrieve list of available languages
     * @return array
     */
    public function languages()
    {
        $service = $this->di['mod_service']('system');
        return $service->getLanguages();
    }
}
