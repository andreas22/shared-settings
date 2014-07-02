<?php namespace Ac\SharedSettings\Repositories;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class APIFiltersRepository implements APIFiltersRepositoryInterface{

    /*
     * @var Ac\SharedSettings\Repositories\DataRepositoryInterface
     */
    private $data;

    /*
     * @var Ac\SharedSettings\Repositories\APIUsersRepositoryInterface
     */
    private $apiuser;

    public function __construct(DataRepositoryInterface $data, APIUsersRepositoryInterface $apiuser)
    {
        $this->data = $data;
        $this->apiuser = $apiuser;
    }
    /**
     * Check if given coded exists
     *
     * @param $code
     * @return bool
     */
    public function validateIfDataCodeExists($code)
    {
        $result = $this->data->findByCode($code);
        return empty($result) ? false : true;
    }

    /**
     * Check if given coded are private
     *
     * @param $code
     * @return bool
     */
    public function validateIfDataIsPrivate($code)
    {
        $result = $this->data->findByCode($code);
        return $result->private ? true : false;
    }

    /**
     * Check if given username from specified IP is allowed
     *
     * @param $username
     * @param $ip
     * @return bool
     */
    public function validateIfIncomingIPAllowed($username, $ip)
    {
        $result = $this->apiuser->findByUsername($username);

        if(!empty($result))
        {
            if($result->address == $ip /*Exact match*/ ||
                strstr($result->address, $ip) /*In a list*/ ||
                $result->address == '*' /*Any ip is allowed*/)
            {
                return true;
            }
            /*Inside a given range*/
            elseif(strstr($result->address, '-'))
            {
                $ip = ip2long($ip);
                $ip_range = explode('-', $result->address);
                $from = ip2long($ip_range[0]);
                $to = ip2long($ip_range[1]);

                if($ip >= $from && $ip <= $to)
                    return true;
            }
        }
        return false;
    }

    /**
     * Check if given username is active
     *
     * @param $username
     * @return bool
     */
    public function validateIfApiuserIsActive($username)
    {
        $result = $this->apiuser->findByUsername($username);
        return !$result->active ? false : true;
    }

    /**
     * Check if given username has permissions to access the given code
     *
     * @param $username
     * @param $code
     * @return bool
     */
    public function validateIfApiuserHasPermissions($username, $code)
    {
        $result = $this->apiuser->getPermissions($username);
        foreach($result as $value)
        {
            if(strcmp($code, $value->code) == 0)
                return true;
        }
        return false;
    }

    /**
     * Check if given username and secret are valid
     *
     * @param $username
     * @param $secret
     * @return bool
     */
    public function validateIfApiuserValidCredentials($username, $secret)
    {
        $result = $this->apiuser->findByUsername($username);
        $secret_md5 = md5($secret);
        return (empty($result) || strcmp($result->secret, $secret_md5) != 0) ? false : true;
    }
}