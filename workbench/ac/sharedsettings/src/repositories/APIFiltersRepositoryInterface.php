<?php namespace Ac\SharedSettings\Repositories;

interface APIFiltersRepositoryInterface
{
    /**
     * Check if given coded exists
     *
     * @param $code
     * @return json 200 on success, 404 for invalid code
     */
    public function validateIfDataCodeExists($code);

    /**
     * Check if given coded are private
     *
     * @param $code
     * @return json 200 on success, 403 for private data
     */
    public function validateIfDataIsPrivate($code);

    /**
     * Check if given username from specified IP is allowed
     *
     * Examples:
     * Single: 192.168.0.1
     * List (comma separated): 192.168.0.1,192.168.0.2
     * Range (from-to): 192.168.0.1-192.168.0.255
     * Any (with asterisk): *
     *
     * @param $username
     * @param $ip
     * @return json 200 on success, 403 for invalid ip
     */
    public function validateIfIncomingIPAllowed($username, $ip);

    /**
     * Check if given username is active
     *
     * @param $username
     * @return json 200 on success, 403 is use is not active
     */
    public function validateIfApiuserIsActive($username);

    /**
     * Check if given username has permissions to access the given code
     *
     * @param $username
     * @param $code
     * @return json 200 on success, 403 for inefficient permissions
     */
    public function validateIfApiuserHasPermissions($username, $code);

    /**
     * Check if given username and secret are valid
     *
     * @param $username
     * @param $secret
     * @return json 200 on success, 404 for invalid user credentials
     */
    public function validateIfApiuserValidCredentials($username, $secret);
} 