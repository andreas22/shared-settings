<?php namespace Ac\SharedSettings\Repositories;

interface DataRepositoryInterface
{
    /**
     * Get data by id
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Get data by code
     *
     * @param $code
     * @return mixed
     */
    public function findByCode($code);

    /**
     * Get all data
     *
     * @param int $paginate
     * @return mixed
     */
    public function all($paginate = 15);

    /**
     * Create a new entry of data
     *
     * @param $values
     * @return mixed
     */
    public function create($values);

    /**
     * Save data
     *
     * @param $values
     * @return mixed
     */
    public function save($values);

    /**
     * Delete a data by id
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Get total number of all data
     *
     * @return mixed
     */
    public function total();

    /**
     * Validate input values against model
     *
     * @param $values
     * @return mixed
     */
    public function validate($values);

    /**
     * Get a list by code, of api users allowed to consume the data
     *
     * @param $code
     * @return mixed
     */
    public function getApiUsersAllowed($code);
} 