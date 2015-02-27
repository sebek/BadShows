<?php namespace BadShows\Repositories;

interface RepositoryInterface
{
    public function find($id);
    public function all();
    public function create($values);
    public function update($id, $values);
    public function delete($id);
}
