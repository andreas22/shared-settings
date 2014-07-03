<?php namespace Ac\SharedSettings\Repositories;

use Ac\SharedSettings\Models\Data;
use App;
use Validator;
use DB;

class DbDataRepository implements DataRepositoryInterface
{
    /**
     * Get data by id
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|static
     */
    public function find($id)
    {
        return Data::find($id);
    }

    /**
     * Get data by code
     *
     * @param $code
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findByCode($code)
    {
        return Data::where('code', '=', $code)->first();
    }

    /**
     * Get all data
     *
     * @param int $paginate
     * @return \Illuminate\Pagination\Paginator
     */
    public function all($paginate = 15)
    {
        return Data::paginate($paginate);
    }

    /**
     * Create a new entry of data
     *
     * @param $values
     * @return mixed
     */
    public function create($values)
    {
        $data = new Data();
        $data->private = isset($values['private']) ? $values['private'] : 0;
        $data->code = isset($values['code']) ? $values['code'] : 0;
        $data->title = isset($values['title']) ? $values['title'] : '';
        $data->description = isset($values['description']) ? $values['description'] : '';
        $data->content = isset($values['content']) ? $values['content'] : '{}';
        $data->created_by = $values['created_by'];
        $data->modified_by = $values['modified_by'];
        $data->save();
        return $data->id;
    }

    /**
     * Save data
     *
     * @param $values
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function save($values)
    {
        $data = $this->find($values['id']);

        if($data->code != $values['code'])
            return null;

        $data->title = isset($values['title']) ? $values['title'] : '';
        $data->description = isset($values['description']) ? $values['description'] : '';
        $data->content = isset($values['content']) ? $values['content'] : '{}';
        $data->modified_by = $values['modified_by'];
        $data->private = isset($values['private']) ? $values['private'] : 0;
        $data->save();
        return $data;
    }

    /**
     * Delete a data
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return Data::destroy($id);
    }

    /**
     * Get total number of all data
     *
     * @return int
     */
    public function total()
    {
        return Data::all()->count();
    }

    /**
     * Validate input values against model
     *
     * @param $values
     * @return \Illuminate\Validation\Validator|null
     */
    public function validate($values)
    {
        $validator = Validator::make($values, Data::$rules);

        if ($validator->fails())
            return $validator;
        return null;
    }

    /**
     * Get a list by code, of api users allowed to consume the data
     *
     * @param $code
     * @return array|mixed|static[]
     */
    public function getApiUsersAllowed($code)
    {
        return DB::table('apiuser_data')
            ->join('apiusers', 'apiusers.id', '=', 'apiuser_data.apiuser_id')
            ->join('data', 'data.id', '=', 'apiuser_data.data_id')
            ->select('apiusers.*', 'data.*', 'apiusers.id as apiuser_id')
            ->where('data.code', '=', $code)
            ->where('apiusers.deleted_at', '=', null)
            ->where('data.deleted_at', '=', null)
            ->get();
    }
}