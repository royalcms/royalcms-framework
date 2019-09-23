<?php


namespace Royalcms\Component\Database;

use Illuminate\Database\MySqlConnection as LaravelMySqlConnection;
use Royalcms\Component\Database\Query\Grammars\MySqlGrammar as QueryGrammar;
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
     * Get the default query grammar instance.
     *
     * @return \Royalcms\Component\Database\Query\Grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

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