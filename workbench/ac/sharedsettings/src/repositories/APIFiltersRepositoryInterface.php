<?php namespace Ac\SharedSettings\Repositories;

interface APIFiltersRepositoryInterface
{
    /**
     * Check if given coded exists
     *
     * @param $code
     * @return bool
     */
    public function validateIfDataCodeExists($code);

    /**
     * Check if given coded are private
     *
     * @param $code
     * @return bool
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
     * @return bool
     */
    public function validateIfIncomingIPAllowed($username, $ip);

    /**
     * Check if given username is active
     *
     * @param $username
     * @return bool
     */
    public function validateIfApiuserIsActive($username);

    /**
     * Check if given username has permissions to access the given code
     *
     * @param $username
     * @param $code
     * @return bool
     */
    public function validateIfApiuserHasPermissions($username, $code);

    /**
     * Check if given username and secret are valid
     *
     * @param $username
     * @param $secret
     * @return bool
     */
    public function validateIfApiuserValidCredentials($username, $secret);
} 