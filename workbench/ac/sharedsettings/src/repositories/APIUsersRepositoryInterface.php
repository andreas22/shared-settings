<?php namespace Ac\SharedSettings\Repositories;


interface APIUsersRepositoryInterface
{
    /**
     * Get an api user by id
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Get an api user by username
     *
     * @param $username
     * @return mixed
     */
    public function findByUsername($username);

    /**
     * Get a list of all api users
     *
     * @param int $paginate
     * @return mixed
     */
    public function all($paginate = 15);

    /**
     * Create a new api user
     *
     * @param $values
     * @return mixed
     */
    public function create($values);

    /**
     * Update an api user information
     *
     * @param $values
     * @return mixed
     */
    public function save($values);

    /**
     * Delete an api user
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Add a permission to an api user
     *
     * @param $apiuser_id
     * @param $permission_id
     * @return mixed
     */
    public function addPermission($apiuser_id, $permission_id);

    /**
     * Remove a permission from an api user
     *
     * @param $apiuser_id
     * @param $permission_id
     * @return mixed
     */
    public function removePermission($apiuser_id, $permission_id);

    /**
     * Get the total number of api users
     *
     * @return mixed
     */
    public function total();

    /**
     * Validate input values
     *
     * @param $values
     * @return mixed
     */
    public function validate($values);

    /**
     * Get a list of permissions for the specified api user, return null if none is available
     *
     * @param $username
     * @return mixed
     */
    public function getPermissions($username);
} 