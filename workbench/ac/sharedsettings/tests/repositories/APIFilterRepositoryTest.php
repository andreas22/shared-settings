<?php

require_once __DIR__ .'/../autoload.php';

class APIFilterRepositoryTest extends TestCase
{
    private $apiFilter;

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
            array('active' => 0, 'username' => 'user5', 'description' => 'this is user5 not active','callback_url' => 'http://www.skype.com','address' => '127.0.0.1-127.0.0.10','created_at' => '2014-06-01 00:00:00','updated_at' => '2014-06-01 00:00:00','created_by' => '1','modified_by' => '1','deleted_at' => NULL,'secret' => '81dc9bdb52d04dc20036dbd8313ed055')
        );

        $this->apiFilter = $this->app->make('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface');

    }

	public function testValidateIfDataCodeExists()
	{
        //Code exists
        echo "\n\n\tTesting an existing code";
        $results = $this->apiFilter->validateIfDataCodeExists('PRV1');
        $this->assertTrue($results);

        //Code do not exists
        echo "\n\n\tTesting a code that do not exists";
        $results = $this->apiFilter->validateIfDataCodeExists(111111111111111);
        $this->assertTrue(!$results);

        echo "\n\n";
	}

    public function testValidateIfDataIsPrivate()
    {
        //Data is private
        echo "\n\n\tTesting that data are private";
        $results = $this->apiFilter->validateIfDataIsPrivate('PRV1');
        $this->assertTrue($results);

        //Data is public
        echo "\n\n\tTesting that data are not private";
        $results = $this->apiFilter->validateIfDataIsPrivate('PUB1');
        $this->assertTrue(!$results);

        echo "\n\n";
    }

    public function testValidateIfIncomingIPAllowed()
    {
        //Test single IP
        echo "\n\n\tTesting an api user ip allowed from a single ip";
        $username = 'user2';
        $ip = '127.0.0.1';
        $results = $this->apiFilter->validateIfIncomingIPAllowed($username, $ip);
        $this->assertTrue($results);

        //Test list of IPs
        echo "\n\n\tTesting an api user ip allowed from a list of ip's";
        $username = 'user3';
        $ip = '127.0.0.1';
        $results1 = $this->apiFilter->validateIfIncomingIPAllowed($username, $ip);
        $this->assertTrue($results1);

        //Test range of IPs
        echo "\n\n\tTesting an api user ip allowed from a range of ip's";
        $username = 'user4';
        $ip = '127.0.0.1';
        $results2 = $this->apiFilter->validateIfIncomingIPAllowed($username, $ip);
        $this->assertTrue($results2);

        //Test any IP
        echo "\n\n\tTesting an api user ip allowed from any ip";
        $username = 'user2';
        $ip = '127.0.0.1';
        $results3 = $this->apiFilter->validateIfIncomingIPAllowed($username, $ip);
        $this->assertTrue($results3);

        echo "\n\n\tTesting an api user ip not in the allowed list";
        $username = 'user1';
        $ip = '127.0.0.10';
        $results = $this->apiFilter->validateIfIncomingIPAllowed($username, $ip);
        $this->assertTrue(!$results);

        echo "\n\n";
   }

    public function testValidateIfApiuserIsActive()
    {
        /*
         * Active
         */
        echo "\n\n\tTesting an active user";
        $username = 'user1';
        $results = $this->apiFilter->validateIfApiuserIsActive($username);
        $this->assertTrue($results);

        /*
         * Inactive
         */
        echo "\n\n\tTesting an inactive user";
        $username = 'user5';
        $results = $this->apiFilter->validateIfApiuserIsActive($username);
        $this->assertTrue(!$results);

        echo "\n\n";
    }

    public function testValidateIfApiuserHasPermissions()
    {
        $user = \Ac\SharedSettings\Models\ApiUser::find(1);
        $user->data()->attach(1);

        /*
         * Has Permissions
         */
        echo "\n\n\tTesting a user that has permissions";
        $username = 'user1';
        $code = 'PRV1';
        $results = $this->apiFilter->validateIfApiuserHasPermissions($username, $code);
        $this->assertTrue($results);

        /*
         * Has no permissions
         */
        echo "\n\n\tTesting a user that has no permissions";
        $username = 'user1';
        $code = 'PUB1';
        $results = $this->apiFilter->validateIfApiuserHasPermissions($username, $code);
        $this->assertTrue(!$results);

        echo "\n\n";
    }

    public function testValidateIfApiuserValidCredentials()
    {
        echo "\n\n\tTesting valid api user credentials";
        $username = 'user1';
        $secret = '1234';
        $results = $this->apiFilter->validateIfApiuserValidCredentials($username, $secret);
        $this->assertTrue($results);

        echo "\n\n\tTesting non valid api user credentials";
        $username = 'user1';
        $secret = '12343';
        $results = $this->apiFilter->validateIfApiuserValidCredentials($username, $secret);
        $this->assertTrue(!$results);

        echo "\n\n";
    }
}
