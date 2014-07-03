<?php

require_once __DIR__ .'/../autoload.php';

class DbNotificationsRepositoryTest extends TestCase
{
    private $api;

    private $apiusers;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('authentication:install');
        Artisan::call('migrate', array('--bench'=>'ac/sharedsettings'));

        \Ac\SharedSettings\Models\Data::create(
            array('code' => 'PRV1', 'title' => 'Private Data','description' => 'This is my private data','content' => '{"servers":{"real1":{"domain":"dc01","from":"127.0.0.1","to":"127.0.0.255"},"real2":"1.2.12.1"},"asd":"sdada","aaaaa":["adsada",22321]}','created_at' => '2014-07-01 00:00:00','updated_at' => '2014-07-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL, 'private' => 1)
        );

        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 1, 'username' => 'user1', 'description' => 'this is user1','callback_url' => 'http://www.google.com','address' => '127.0.0.1','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        $this->api = $this->app->make('Ac\SharedSettings\Repositories\NotificationsRepositoryInterface');
        $this->apiusers = $this->app->make('Ac\SharedSettings\Repositories\APIUsersRepositoryInterface');
    }

	public function testCreate()
	{
        /*
         * Testing successful send
         */
        $data_id = 1;

        $this->apiusers->addPermission(1, 1);

        echo "\n\nTesting create a notification\n";
        $id = $this->api->create($data_id, 1, date('Y-m-d H:i:s'));
        $this->assertTrue($id == 1);

        echo "\nTesting check for pending notifications\n";
        $results = $this->api->hasPendingNotification($data_id);
        $this->assertTrue(!empty($results));

        echo "\nTesting sending a notification\n";

        $this->api->send($id, 1);

        echo "\nTesting check for pending notifications\n";
        $results = $this->api->hasPendingNotification($data_id);
        $this->assertTrue(empty($results));

        echo "\n\n";
	}
}
