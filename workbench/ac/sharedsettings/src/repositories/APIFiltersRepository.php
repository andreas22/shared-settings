<?php namespace Ac\SharedSettings\Repositories;

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
     * @return json 302 if found, 404 for invalid code
     */
    public function validateIfDataCodeExists($code)
    {
        $result = $this->data->findByCode($code);

        if(empty($result))
            return ['result' => [
                'status' => HttpCodes::HTTP_NOT_FOUND,
                'error' => 'Invalid code request!']
            ];
        return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
    }

    /**
     * Check if given coded are private
     *
     * @param $code
     * @return json 302 if found, 403 for private data
     */
    public function validateIfDataIsPrivate($code)
    {
        $result = $this->data->findByCode($code);

        if($result->private)
            return ['result' => [
                'status' => HttpCodes::HTTP_FORBIDDEN,
                'error' => 'Data are private, permission is deny!']
            ];
        return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
    }

    /**
     * Check if given username from specified IP is allowed
     *
     * @param $username
     * @param $ip
     * @return json 302 if found, 403 for invalid ip
     */
    public function validateIfIncomingIPAllowed($username, $ip)
    {
        $found = false;
        $result = $this->apiuser->findByUsername($username);

        if( $result->address == $ip /*Exact match*/ ||
            strstr($result->address, $ip) /*In a list*/ ||
            $result->address == '*' /*Any ip is allowed*/)
        {
            $found = true;
        }
        /*Inside a given range*/
        elseif(strstr($result->address, '-'))
        {
            $ip = ip2long($ip);
            $ip_range = explode('-', $result->address);
            $from = ip2long($ip_range[0]);
            $to = ip2long($ip_range[1]);

            if($ip >= $from && $ip <= $to)
                $found = true;
        }

        if(!$found)
            return ['result' => [
                'status' => HttpCodes::HTTP_FORBIDDEN,
                'error' => 'Invalid IP address!']
            ];
        return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
    }

    /**
     * Check if given username is active
     *
     * @param $username
     * @return json 302 if found, 403 is use is not active
     */
    public function validateIfApiuserIsActive($username)
    {
        $result = $this->apiuser->findByUsername($username);

        if(!$result->active)
            return ['result' => [
                'status' => HttpCodes::HTTP_FORBIDDEN,
                'error' => 'API user is not active!']
            ];
        return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
    }

    /**
     * Check if given username has permissions to access the given code
     *
     * @param $username
     * @param $code
     * @return json 302 if found, 403 for inefficient permissions
     */
    public function validateIfApiuserHasPermissions($username, $code)
    {
        $result = $this->apiuser->getPermissions($username);
        foreach($result as $value)
        {
            if(strcmp($code, $value->code) == 0)
                return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
        }

        return ['result' => [
            'status' => HttpCodes::HTTP_FORBIDDEN,
            'error' => 'Inefficient permissions!']
        ];
    }

    /**
     * Check if given username and secret are valid
     *
     * @param $username
     * @param $secret
     * @return json 302 if found, 404 for invalid user credentials
     */
    public function validateIfApiuserValidCredentials($username, $secret)
    {
        $result = $this->apiuser->findByUsername($username);
        $secret_md5 = md5($secret);

        if(empty($result) || strcmp($result->secret, $secret_md5) != 0)
            return ['result' => [
                'status' => HttpCodes::HTTP_FORBIDDEN,
                'data' => null,
                'error' => 'Invalid credentials!']
            ];
        return ['result' => ['status' => HttpCodes::HTTP_FOUND]];
    }
}