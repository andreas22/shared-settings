<?php

require_once __DIR__ .'/../autoload.php';

use Ac\SharedSettings\Common\ArrayHelper;

class ArrayHelperTest extends TestCase {

	public function testGetValueByKey()
	{
        $values = ['name1' => 'value1', 'name2' => 'value2', 'name3' => ['name31' => 'value31', 'name32' => ['name41' => 'value41']],];

        $first_level = ArrayHelper::getValueByKey('name2', $values);
		$this->assertEquals('value2', $first_level);

        $second_level = ArrayHelper::getValueByKey('name31', $values);
        $this->assertEquals('value31', $second_level);

        $third_level = ArrayHelper::getValueByKey('name41', $values);
        $this->assertEquals('value41', $third_level);

        $not_found = ArrayHelper::getValueByKey('name422221', $values);
        $this->assertTrue($not_found == false);
	}

}
