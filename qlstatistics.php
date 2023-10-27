<?php
/**
 * @package        plg_system_qlstatistics
 * @copyright    Copyright (C) 2024 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

//no direct access
use Hoochicken\ParameterBag\ParameterBag;
use Hoochicken\WebStatistics\WebStatistics;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.plugin.plugin');

class plgSystemQlstatistics extends CMSPlugin
{
    protected const AREA_ADMIN = 'administrator';
    protected const AREA_SITE = 'site';
    protected const AREA_BOTH = 'both';
    protected const WEBSTAT_TABLE = '#__ql_web_statistics';
    protected const MAX_TIME_LAST_REQUEST_DIFF = 20;

    /** @var \Joomla\Registry\Registry $params */
    public $params;
    public array $data;
    public string $sessionId;
    public \Joomla\Input\Input $input;
    public ?CMSApplicationInterface $app;
    public ?Joomla\Registry\Registry $config;
    public \Joomla\CMS\Session\Session $session;

    public function __construct(&$subject, $config)
    {
        $this->app = Factory::getApplication();
        $lang = $this->app->getLanguage();
        $lang->load('plg_content_qlstatistics', dirname(__FILE__));
        $this->config = Factory::getContainer()->get('config');
        $this->session = Factory::getApplication()->getSession();
        $this->sessionId = session_id();
        $this->input = $this->app->getInput();
        $this->initSession();
        parent::__construct($subject, $config);
    }

    public function onBeforeCompileHead()
    {
        $area = $this->params->get('area', static::AREA_SITE);
        if (
            ($this->app->isClient(static::AREA_ADMIN) && !in_array($area, [static::AREA_ADMIN, static::AREA_BOTH,]))
            ||
            ($this->app->isClient(static::AREA_SITE) && !in_array($area, [static::AREA_SITE, static::AREA_BOTH,]))
        ) {
            return;
        }

        // Joomla! renders everything twice ... bad
        // therefore we check for the last render; if within 2sek, we ignore the call
        // hack, butwell
        if ($this->checkSessionIdentical() && $this->onlyLittleTimeBetweenRequests()) {
            return;
        }
        $this->setSession();

        /** @var MenuItem $active */
        $active = $this->app->getMenu()->getActive();
        if (is_null($active)) {
            return;
        }

        require_once __DIR__ . '/vendor/autoload.php';
        $webstatistics = $this->initWebstatTable();
        $webstatistics->setSessionId($this->getSessionId());
        $webstatistics->addEntry($active->title ?? '', $active->id ?? '');
    }

    protected function checkSessionIdentical(): bool
    {
        return isset($_SESSION['qlstatistics']) && $_SESSION['qlstatistics']['sessionId'] === $this->getSessionId();
    }

    protected function onlyLittleTimeBetweenRequests(): bool
    {
        return $_SERVER['REQUEST_TIME'] - $_SESSION['qlstatistics']['lastStartTime'] < static::MAX_TIME_LAST_REQUEST_DIFF;
    }

    protected function initSession()
    {
        if (!isset($_SESSION['qlstatistics']) || (isset($_SESSION['qlstatistics']['lastStartTime']) && !empty($_SESSION['qlstatistics']['lastStartTime']))) {
            return;
        }
        $this->setSession();
    }

    protected function setSession()
    {
        $_SESSION['qlstatistics'] = [
            'sessionId' => $this->getSessionId(),
            'lastStartTime' => $_SERVER['REQUEST_TIME'] ?? 0,
        ];
    }

    private function initWebstatTable(): WebStatistics
    {
        $webstatistics = new WebStatistics();
        $webstatistics->initDb($this->getDbHost(), $this->getDbDatabase(), $this->getDbUser(), $this->getDbPassword());
        $webstatistics->setServer(new ParameterBag($_SERVER));
        $webstatistics->setTable($this->getDbTablename());
        if (!$webstatistics->tableExists()) {
            $webstatistics->createTable();
        }
        return $webstatistics;
    }

    protected function getDbTablename(): string
    {
        return str_replace('#__', sprintf('%s', $this->getDbPrefix()), static::WEBSTAT_TABLE);
    }

    protected function getDbHost(): string
    {
        return $this->config->get('host', '');
    }

    protected function getDbPrefix(): string
    {
        return $this->config->get('dbprefix', '');
    }

    protected function getDbDatabase(): string
    {
        return $this->config->get('db', '');
    }

    protected function getDbUser(): string
    {
        return $this->config->get('user', '');
    }

    protected function getDbPassword(): string
    {
        return $this->config->get('password', '');
    }

    protected function getSessionId(): string
    {
        return $this->sessionId;
    }
}