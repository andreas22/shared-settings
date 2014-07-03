<?php namespace Ac\SharedSettings\Repositories;

use Ac\SharedSettings\Models\ApiUser;
use App;
use Validator;

class DbAPIUsersRepository implements APIUsersRepositoryInterface {

    /**
     * Get an api user by id
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|static
     */
    public function find($id)
    {
        return ApiUser::find($id);
    }

    /**
     * Get an api user by username
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function findByUsername($username)
    {
        return ApiUser::where('username', '=', $username)->first();
    }

    /**
     * Get a list of all api users
     *
     * @param int $paginate
     * @return \Illuminate\Pagination\Paginator
     */
    public function all($paginate = 15)
    {
        return ApiUser::paginate($paginate);
    }

    /**
     * Create a new api user
     *
     * @param $values
     * @return Ac\SharedSettings\Models\ApiUser
     */
    public function create($values)
    {
        $apiuser = new ApiUser;
        $apiuser->active = isset($values['active']) ? 1 : 0;
        $apiuser->description = isset($values['description']) ? $values['description'] : '';
        $apiuser->username = isset($values['username']) ? $values['username'] : '';
        $apiuser->secret = isset($values['secret']) && !empty($values['secret']) ? md5($values['secret']) : '';
        $apiuser->callback_url = isset($values['callback_url']) ? $values['callback_url'] : '';
        $apiuser->address = isset($values['address']) ? $values['address'] : '';
        $apiuser->created_by = $values['created_by'];
        $apiuser->modified_by = $values['modified_by'];
        $apiuser->save();
        return $apiuser->id;
    }

    /**
     * Update an api user information
     *
     * @param $values
     * @return Ac\SharedSettings\Models\ApiUser
     */
    public function save($values)
    {
        $apiuser = ApiUser::find($values['id']);
        $apiuser->active = isset($values['active']) && $values['active'] ? 1 : 0;
        $apiuser->description = isset($values['description']) ? $values['description'] : '';
        $apiuser->username = isset($values['username']) ? $values['username'] : '';
        if(isset($values['secret']) && !empty($values['secret']) &&
            ($apiuser->secret != md5($values['secret']) && $apiuser->secret != $values['secret']))
            $apiuser->secret = md5($values['secret']);
        $apiuser->callback_url = isset($values['callback_url']) ? $values['callback_url'] : '';
        $apiuser->address = isset($values['address']) ? $values['address'] : '';
        $apiuser->modified_by = $values['modified_by'];
        $apiuser->save();
        return $apiuser;
    }

    /**
     * Delete an api user
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return ApiUser::destroy($id);
    }

    /**
     * Add a permission to an api user
     *
     * @param $apiuser_id
     * @param $permission_id
     * @return mixed|void
     */
    public function addPermission($apiuser_id, $permission_id)
    {
        $apiUser = ApiUser::find($apiuser_id);
        return $apiUser->data()->attach($permission_id);
    }

    /**
     * Remove a permission from an api user
     *
     * @param $apiuser_id
     * @param $permission_id
     * @return int
     */
    public function removePermission($apiuser_id, $permission_id)
    {
        $apiUser = ApiUser::find($apiuser_id);
        return $apiUser->data()->detach($permission_id);
    }

    /**
     * Get the total number of api users
     *
     * @return int
     */
    public function total()
    {
       return ApiUser::all()->count();
    }

    /**
     * Validate input values
     *
     * @param $values
     * @return \Illuminate\Validation\Validator|null
     */
    public function validate($values)
    {
        $validator = Validator::make($values, ApiUser::$rules);
        if($validator->fails())
            return $validator;
        return null;
    }

    /**
     * Get a list of permissions for the specified api user, return null if none is available
     *
     * @param $username
     * @return null
     */
    public function getPermissions($username)
    {
        $apiuser = $this->findByUsername($username);
        if($apiuser)
            return $apiuser->data;
        return null;
    }
}