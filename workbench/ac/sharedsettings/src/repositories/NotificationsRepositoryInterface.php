<?php namespace Ac\SharedSettings\Repositories;

interface NotificationsRepositoryInterface
{

    /**
     * Creates a push notification
     *
     * @param $data_id
     * @param $created_by
     * @param $created_at
     * @return mixed
     */
    public function create($data_id, $created_by, $created_at);

    /**
     * Send a push notification
     *
     * @param $id
     * @param $logged_in_user
     * @return mixed
     */
    public function send($id, $logged_in_user);

    /**
     * Returns true if on last changes notification has not been send
     *
     * @param $data_id
     * @return mixed
     */
    public function hasPendingNotification($data_id);

} 