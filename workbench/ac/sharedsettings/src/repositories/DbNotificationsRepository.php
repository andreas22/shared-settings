<?php namespace Ac\SharedSettings\Repositories;

use Ac\SharedSettings\Models\Notification;
use Ac\SharedSettings\Repositories\DataRepositoryInterface;
use Log;

class DbNotificationsRepository implements NotificationsRepositoryInterface
{
    /*
     * Ac\SharedSettings\Repositories\DataRepositoryInterface
     */
    private $data;

    public function __construct(DataRepositoryInterface $data)
    {
        $this->data = $data;
    }

    /**
     * Creates a push notification
     *
     * @param $data_id
     * @param $created_by
     * @param $created_at
     * @return mixed
     */
    public function create($data_id, $created_by, $created_at)
    {
        $model = new Notification();
        $model->data_id = $data_id;
        $model->created_by = $created_by;
        $model->modified_by = $created_by;
        $model->recipients = "";
        $model->save();
        return $model->id;
    }

    private function save($id, $sent, $recipients, $modified_by)
    {
        $model = Notification::find($id);
        $model->sent = $sent;
        $model->recipients = $recipients;
        $model->modified_by = $modified_by;
        $model->save();
        return $model;
    }

    /**
     * Send a push notification
     *
     * @param $id
     * @param $logged_in_user
     * @return mixed
     */
    public function send($id, $logged_in_user)
    {
        $notification = Notification::find($id);
        if($notification)
        {
            $recipients_list = [];
            $data = $this->data->find($notification->data_id);
            $recipients = $this->data->getApiUsersAllowed($data->code);

            foreach($recipients as $recipient)
            {
                if(!empty($recipient->callback_url) &&
                    $recipient->active == 1)
                {
                    $recipients_list[] = ['apiuser_id' => $recipient->apiuser_id,
                                          'callback_url' => $recipient->callback_url];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $recipient->callback_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_exec($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $status = $status_code == 302 ? 1 : 0;
                    curl_close($ch);
                }
            }

            $this->save($id, $status, json_encode($recipients_list), $logged_in_user);
        }
    }

    /**
     * Returns true if on last changes notification has not been send
     *
     * @param $data_id
     * @return mixed
     */
    public function hasPendingNotification($data_id)
    {
        $notification = Notification::where('data_id', '=', $data_id)
            ->orderBy('updated_at', 'desc')
            ->first();

        if(!empty($notification) && $notification->sent == 0)
            return $notification->updated_at;
        return false;
    }
}