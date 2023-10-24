<?php
/**
 * @package		plg_system_qlstatistics
 * @copyright	Copyright (C) 2024 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

//no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.plugin.plugin');

class plgSystemQlstatistics extends JPlugin
{
    public \Joomla\Registry\Registry $params;
    public array $data;

    public function __construct(& $subject, $config)
    {
        $lang = Factory::getApplication()->getLanguage();
        $lang->load('plg_content_qlstatistics', dirname(__FILE__));
        parent::__construct($subject, $config);
    }

    public function includeScripts()
    {
        if ($this->params->get('jquery', false)) {
            HTMLHelper::_('jquery.framework');
        }
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerAndUseStyle('plg_system_qlstatistics', 'plg_system_qlstatistics/qlstatistics.css');
        $wa->registerAndUseScript('plg_system_qlstatistics', 'plg_system_qlstatistics/qlstatistics.js');
    }

    /**
     * 
     */
    public function onAfterRender()
    {

    }
}