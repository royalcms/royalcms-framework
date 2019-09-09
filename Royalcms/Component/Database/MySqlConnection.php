<?php


namespace Royalcms\Component\Database;

use Illuminate\Database\MySqlConnection as LaravelMySqlConnection;
use PDO;
use Royalcms\Component\Database\Query\Builder as QueryBuilder;

class MySqlConnection extends LaravelMySqlConnection
{

    /**
     * The default fetch mode of the connection.
     *
     * @var int
     */
    protected $fetchMode = PDO::FETCH_ASSOC;
    

    /**
     * Get a new query builder instance.
     *
     * @return \Royalcms\Component\Database\Query\Builder
     */
    public function query()
    {
        return new QueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

}