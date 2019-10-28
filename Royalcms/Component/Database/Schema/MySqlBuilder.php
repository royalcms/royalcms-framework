<?php


namespace Royalcms\Component\Database\Schema;


class MySqlBuilder extends \Illuminate\Database\Schema\MySqlBuilder
{

    /**
     * Create a new command set with a Closure.
     *
     * @param  string  $table
     * @param  \Closure|null  $callback
     * @return \Royalcms\Component\Database\Schema\MySqlBlueprint
     */
    protected function createBlueprint($table, Closure $callback = null)
    {
        $prefix = $this->connection->getConfig('prefix_indexes')
            ? $this->connection->getConfig('prefix')
            : '';

        if (isset($this->resolver)) {
            return call_user_func($this->resolver, $table, $callback, $prefix);
        }

        return new Blueprint($table, $callback, $prefix);
    }

    /**
     * Convert a table charset and collation on the schema.
     *
     * @param  string  $from
     * @param  string  $charset
     * @param  string  $collation
     * @return void
     */
    public function convertEncoding($from, $charset, $collation)
    {
        $blueprint = $this->createBlueprint($from);

        $blueprint->convertEncoding($charset, $collation);

        $this->build($blueprint);
    }

    /**
     * Convert a table engine on the schema.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    public function convertEngine($from, $to)
    {
        $blueprint = $this->createBlueprint($from);

        $blueprint->convertEngine($to);

        $this->build($blueprint);
    }

    /**
     * Add a table comment on the schema.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    public function tableComment($from, $comment)
    {
        $blueprint = $this->createBlueprint($from);

        $blueprint->tableComment($comment);

        $this->build($blueprint);
    }

}