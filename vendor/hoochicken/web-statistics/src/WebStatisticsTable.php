<?php

namespace Hoochicken\WebStatistics;

use Hoochicken\Dbtable\Database;
use PDO;

class WebStatisticsTable extends Database
{
    const TABLE_NAME = 'statistics';
    const COLUMN_ID = 'id';
    const COLUMN_TITLE = 'title';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_PAGE_URL = 'page_url';
    const COLUMN_SESSION_ID = 'session_id';
    const COLUMN_ENTRY_TIME = 'entry_time';
    const COLUMN_EXIT_TIME = 'exit_time';
    const COLUMN_IP_ADDRESS = 'ip_address';
    const COLUMN_COUNTRY = 'country';
    const COLUMN_COUNTER = 'counter';
    const COLUMN_OPERATING_SYSTEM = 'operating_system';
    const COLUMN_BROWSER = 'browser';
    const COLUMN_BROWSER_VERSION = 'browser_version';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_MAX_ID = 'max_id';
    const COLUMN_MAX_CREATED_AT = 'max_created_at';

    protected static array $definition = [];

    public function __construct(string $host, string $database, string $user, string $password, int $port = self::PORT_DEFAULT)
    {
        parent::__construct($host, $database, $user, $password, $port);
        $this->setTable(static::TABLE_NAME);
        $this->setDefinition();
    }

    public function createTableStatistics()
    {
        $this->createTable(static::getTable(), static::$definition);
    }

    private function setDefinition()
    {
        static::$definition = [
            static::COLUMN_ID => sprintf('`%s` int(20) NOT NULL', static::COLUMN_ID),
            static::COLUMN_TITLE => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_TITLE),
            static::COLUMN_DESCRIPTION => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_DESCRIPTION),
            static::COLUMN_PAGE_URL => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_PAGE_URL),
            static::COLUMN_SESSION_ID => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_SESSION_ID),
            static::COLUMN_ENTRY_TIME => sprintf('`%s` datetime DEFAULT NULL', static::COLUMN_ENTRY_TIME),
            static::COLUMN_EXIT_TIME => sprintf('`%s` datetime DEFAULT NULL', static::COLUMN_EXIT_TIME),
            static::COLUMN_IP_ADDRESS => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_IP_ADDRESS),
            static::COLUMN_COUNTRY => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_COUNTRY),
            static::COLUMN_OPERATING_SYSTEM => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_OPERATING_SYSTEM),
            static::COLUMN_BROWSER => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_BROWSER),
            static::COLUMN_BROWSER_VERSION => sprintf('`%s` varchar(255) DEFAULT NULL', static::COLUMN_BROWSER_VERSION),
            static::COLUMN_CREATED_AT => sprintf('`%s` timestamp DEFAULT NULL DEFAULT current_timestamp()', static::COLUMN_CREATED_AT),
        ];
    }

    public function getDefinition()
    {
        return static::$definition;
    }

    public function add(string $title = '', string $description = '')
    {
        $this->addEntry($title, $description);
    }
}