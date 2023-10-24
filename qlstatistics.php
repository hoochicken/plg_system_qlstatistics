<?php
/**
 * @package		plg_system_qlstatistics
 * @copyright	Copyright (C) 2024 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

//no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.plugin.plugin');

class plgSystemQlstatistics extends CMSPlugin
{
    /** @var \Joomla\Registry\Registry $params */
    public $params;
    public array $data;

    public function __construct(& $subject, $config)
    {
        $lang = Factory::getApplication()->getLanguage();
        $lang->load('plg_content_qlstatistics', dirname(__FILE__));
        parent::__construct($subject, $config);
    }


    public function onAfterRender()
    {
        $app = Factory::getApplication();
        if ($app->isClient('admin')) {
            return;
        }

        $active = $app->getMenu()->getActive();

        $data = [
            'menu_item_id' => $active->id,
            'link' => $active->link,
            'type' => $active->component,
            'component' => $active->component,
        ];

    }
}