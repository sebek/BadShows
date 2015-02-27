<?php namespace BadShows\Repositories;

use \PDO;
use \FluentPDO;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $pdo;
    protected $fpdo;

    public function __construct($basePath)
    {
        // Not really a kosher way to do it.
        // Should have been injected, but for this little example it's not necessary
        $this->pdo = new PDO("sqlite:$basePath/database/badshows.sqlite3");
        $this->fpdo = new FluentPDO($this->pdo);
    }

    /**
     * Find row by id
     *
     * @param $id
     * @return array
     */
    public function find($id)
    {
        $query = $this->fpdo->from($this->table)->where("id", $id);
        return $this->toArray($query);
    }

    /**
     * Fetch every in table
     *
     * @return array
     */
    public function all()
    {
        $query = $this->fpdo->from($this->table)->orderBy("id desc");
        return $this->toArray($query);
    }

    /**
     * Insert row into table
     *
     * @param $values
     * @return mixed
     */
    public function create($values)
    {
        $query = $this->fpdo->insertInto($this->table)->values($values);
        return $query->execute();
    }

    /**
     * Update a row
     *
     * @param $id
     * @param $values
     * @return mixed
     */
    public function update($id, $values)
    {
        $query = $this->fpdo->update($this->table)->set($values)->where("id", $id);
        return $query->execute();
    }

    /**
     * Delete a row
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $query = $this->fpdo->deleteFrom($this->table, $id);
        return $query->execute();
    }

    /**
     * Converts a query to an array
     *
     * @param $results
     * @return array
     */
    protected function toArray($results)
    {
        $array = [];

        foreach ($results as $row) {
            $array[] = $row;
        }

        return $array;
    }
}
