<?php namespace BadShows\Repositories;

class ShowsRepository extends AbstractRepository
{
    protected $table = "shows";

    /**
     * Find a cat by name
     *
     * @param $name
     * @return mixed
     */
    public function findByName($name)
    {
        $query = $this->fpdo->from($this->table)->where('name', $name);
        return $this->toArray($query);
    }
}
