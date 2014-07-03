<?php

require_once __DIR__ .'/../autoload.php';

class DbAPIUserRepositoryTest extends TestCase
{
    private $api;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('authentication:install');
        Artisan::call('migrate', array('--bench'=>'ac/sharedsettings'));

        \Ac\SharedSettings\Models\Data::create(
            array('code' => 'PRV1', 'title' => 'Private Data','description' => 'This is my private data','content' => '{"servers":{"real1":{"domain":"dc01","from":"127.0.0.1","to":"127.0.0.255"},"real2":"1.2.12.1"},"asd":"sdada","aaaaa":["adsada",22321]}','created_at' => '2014-07-01 00:00:00','updated_at' => '2014-07-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL, 'private' => 1)
        );

        \Ac\SharedSettings\Models\Data::create(
            array('code' => 'PUB1', 'title' => 'Public Data','description' => 'This is my public data','content' => '{"servers":{"real1":{"domain":"dc01","from":"127.0.0.1","to":"127.0.0.255"},"real2":"1.2.12.1"},"asd":"sdada","aaaaa":["adsada",22321]}','created_at' => '2014-07-01 00:00:00','updated_at' => '2014-07-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL, 'private' => 0)
        );

        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 1, 'username' => 'user1', 'description' => 'this is user1','callback_url' => 'http://www.google.com','address' => '127.0.0.1','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 1, 'username' => 'user2', 'description' => 'this is user2','callback_url' => 'http://www.msn.com','address' => '*','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );
        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 1, 'username' => 'user3', 'description' => 'this is user3','callback_url' => 'http://www.skype.com','address' => '127.0.0.1,127.0.0.2','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 1, 'username' => 'user4', 'description' => 'this is user4','callback_url' => 'http://www.skype.com','address' => '127.0.0.1-127.0.0.10','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        \Ac\SharedSettings\Models\ApiUser::create(
            array('active' => 0, 'username' => 'user5', 'description' => 'this is user5 not active','callback_url' => 'http://www.skype.com','address' => '127.0.0.1-127.0.0.10','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => '2014-06-01 00:00:00','secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        $this->api = $this->app->make('Ac\SharedSettings\Repositories\APIUsersRepositoryInterface');

    }

	public function testFind()
	{
        echo "\n\n\tTesting api user exists";
        $results = $this->api->find(1);
        $this->assertTrue((int)$results->id == 1);

        echo "\n\n\tTesting api user do not exists";
        $results = $this->api->find(10000);
        $this->assertTrue(empty($results));

        echo "\n\n";
	}

    public function testFindByUsername()
    {
        echo "\n\n\tTesting api user exists";
        $results = $this->api->findByUsername('user1');
        $this->assertTrue((int)$results->id == 1);

        echo "\n\n\tTesting api user do not exists";
        $results = $this->api->find('donotexists');
        $this->assertTrue(empty($results));

        echo "\n\n";
    }

    public function testAll()
    {
        echo "\n\n\tTesting get all api users";
        $results = $this->api->all();
        $this->assertEquals(4, $results->count());

        echo "\n\n";
    }

    public function testCreate()
    {
        $values = [];
        $values['active'] = 1;
        $values['description'] = 'this is a description';
        $values['username'] = 'tester12345';
        $values['secret'] = '1234';
        $values['callback_url'] = 'callback_url';
        $values['address'] = 'address';
        $values['created_by'] = 1;
        $values['modified_by'] = 1;
        $id = $this->api->create($values);

        $this->assertTrue(!empty($id));

        $results = $this->api->find($id);

        $this->assertEquals($values['active'], $results->active);
        $this->assertEquals($values['description'], $results->description);
        $this->assertEquals($values['username'], $results->username);
        $this->assertEquals(md5($values['secret']), $results->secret);
        $this->assertEquals($values['callback_url'], $results->callback_url);
        $this->assertEquals($values['address'], $results->address);
        $this->assertEquals($values['created_by'], $results->created_by);
        $this->assertEquals($values['modified_by'], $results->modified_by);

        echo "\n\n";
    }

    public function testSave()
    {
        $values['id'] = 1;
        $values['active'] = 0;
        $values['description'] = 'this is a description';
        $values['username'] = 'tester123456';
        $values['secret'] = '12345';
        $values['callback_url'] = 'callback_url1';
        $values['address'] = 'address1';
        $values['created_by'] = 1;
        $values['modified_by'] = 1;
        $user = $this->api->save($values);

        $this->assertTrue(!empty($user));

        $results = $this->api->find(1);

        $this->assertEquals($values['active'], $results->active);
        $this->assertEquals($values['description'], $results->description);
        $this->assertEquals($values['username'], $results->username);
        $this->assertEquals(md5($values['secret']), $results->secret);
        $this->assertEquals($values['callback_url'], $results->callback_url);
        $this->assertEquals($values['address'], $results->address);
        $this->assertEquals($values['created_by'], $results->created_by);
        $this->assertEquals($values['modified_by'], $results->modified_by);

        echo "\n\n";
    }

    public function testDelete()
    {
        $this->api->delete(2);
        $results = $this->api->find(2);
        $this->assertTrue(empty($results));

        echo "\n\n";
    }

    public function testTotal()
    {
        $total = $this->api->total();
        $this->assertEquals(4, $total);

        echo "\n\n";
    }
    public function testValidate()
    {
        $params = [];
        $params['username'] = '1';
        $params['secret'] = '2';
        $params['address'] = '3';

        $result = $this->api->validate($params);
        $this->assertTrue(empty($result));

        unset($params['address']);
        $result = $this->api->validate($params);
        $this->assertTrue(!empty($result));

        echo "\n\n";
    }


    public function testAddPermission()
    {
        $apiuser_id = 4;
        $permission_id = 1;

        echo "\n\n\tTesting add permission";
        $this->api->addPermission($apiuser_id, $permission_id);

        echo "\n\n\tTesting get permissions";
        $permissions = $this->api->getPermissions('user4');

        $added = false;

        foreach($permissions as $p)
        {
            if($p->id == $permission_id)
            {
                $added = true;
                continue;
            }
        }

        $this->assertTrue($added);

        echo "\n\n\tTesting remove permission";
        $this->api->removePermission($apiuser_id, $permission_id);

        echo "\n\n\tTesting get permissions";
        $permissions = $this->api->getPermissions('user4');

        $added = true;

        foreach($permissions as $p)
        {
            if($p->id == $permission_id)
            {
                $added = false;
                continue;
            }
        }

        $this->assertTrue($added);

        echo "\n\n";
    }
}
