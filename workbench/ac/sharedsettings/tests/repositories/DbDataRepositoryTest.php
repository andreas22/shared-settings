<?php

require_once __DIR__ .'/../autoload.php';

class DbDataRepositoryTest extends TestCase
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

        $this->api = $this->app->make('Ac\SharedSettings\Repositories\DataRepositoryInterface');
        $this->apiusers = $this->app->make('Ac\SharedSettings\Repositories\APIUsersRepositoryInterface');
    }

	public function testFind()
	{
        echo "\n\n\tTesting data exists";
        $results = $this->api->find(1);
        $this->assertTrue((int)$results->id == 1);

        echo "\n\n\tTesting data do not exists";
        $results = $this->api->find(10000);
        $this->assertTrue(empty($results));

        echo "\n\n";
	}

    public function testFindByCode()
    {
        echo "\n\n\tTesting data exists";
        $results = $this->api->findByCode('PRV1');
        $this->assertTrue((int)$results->id == 1);

        echo "\n\n\tTesting data do not exists";
        $results = $this->api->findByCode('donotexists');
        $this->assertTrue(empty($results));

        echo "\n\n";
    }

    public function testAll()
    {
        echo "\n\n\tTesting get all data";
        $results = $this->api->all();
        $this->assertEquals(2, $results->count());

        echo "\n\n";
    }

    public function testCreate()
    {
        $values = [];
        $values['private'] = 1;
        $values['code'] = 'TESTCODE';
        $values['title'] = 'title1';
        $values['description'] = 'description1';
        $values['content'] = '{}';
        $values['created_by'] = 1;
        $values['modified_by'] = 1;
        $id = $this->api->create($values);

        $this->assertTrue(!empty($id));

        $results = $this->api->find($id);

        $this->assertEquals($values['private'], $results->private);
        $this->assertEquals($values['code'], $results->code);
        $this->assertEquals($values['title'], $results->title);
        $this->assertEquals($values['description'], $results->description);
        $this->assertEquals($values['content'], $results->content);
        $this->assertEquals($values['created_by'], $results->created_by);
        $this->assertEquals($values['modified_by'], $results->modified_by);

        echo "\n\n";
    }

    public function testSave()
    {
        $values = [];
        $values['id'] = 1;
        $values['private'] = 1;
        $values['code'] = 'PRV1';
        $values['title'] = 'title1';
        $values['description'] = 'description1';
        $values['content'] = '{}';
        $values['created_by'] = 1;
        $values['modified_by'] = 1;
        $data = $this->api->save($values);

        $this->assertTrue(!empty($data));

        $results = $this->api->find(1);

        $this->assertEquals($values['private'], $results->private);
        $this->assertEquals($values['code'], $results->code);
        $this->assertEquals($values['title'], $results->title);
        $this->assertEquals($values['description'], $results->description);
        $this->assertEquals($values['content'], $results->content);
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
        $this->assertEquals(2, $total);

        echo "\n\n";
    }

    public function testValidate()
    {
        $params = [];
        $params['code'] = '1';
        $params['title'] = '2';

        $result = $this->api->validate($params);
        $this->assertTrue(empty($result));

        $params = [];
        $params['code'] = '1';
        $result = $this->api->validate($params);
        $this->assertTrue(!empty($result));

        $params = [];
        $params['title'] = '2';
        $result = $this->api->validate($params);
        $this->assertTrue(!empty($result));

        echo "\n\n";
    }


    public function testGetApiUsersAllowed()
    {
        $this->apiusers->addPermission(1, 1);
        $list = $this->api->getApiUsersAllowed('PRV1');
        $this->assertTrue(!empty($list));

        $list = $this->api->getApiUsersAllowed('PRV222');
        $this->assertTrue(empty($list));

    }
}
