<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\ManualUser;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ManualUserTest extends TestCase
{
    const USER_EMAIL = 'unitest@test.com';

    /**
     * @var ManualUser $user
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new ManualUser();
        // This is not how you're supposed to use factories, but I'm using a non-framework model
        $attributes = UserFactory::new()->definition(); 
        $this->user->assign($attributes);
        $this->user->email = self::USER_EMAIL;
    }

    public function tearDown(): void
    {
        // Delete the user if we saved him to the DB.
        if (!$this->user->isNewRecord()) {
            $this->user->delete();
        }
        parent::tearDown();
    }

    /**
     * Requirement: Get all object properties of model
     */
    public function testGetAllObjectProperties()
    {
        // Confirm that the properties exist in the array
        $properties = $this->user->toArray();
        $this->assertIsArray($properties);
        $this->assertArrayHasKey('id', $properties);
        $this->assertArrayHasKey('first_name', $properties);
        $this->assertArrayHasKey('last_name', $properties);
        $this->assertArrayHasKey('email', $properties);
        $this->assertArrayHasKey('mobile_number', $properties);
        $this->assertArrayHasKey('address', $properties);
        $this->assertArrayHasKey('city', $properties);
        $this->assertArrayHasKey('state', $properties);
        $this->assertArrayHasKey('zip', $properties);
        $this->assertArrayHasKey('country', $properties);
        $this->assertArrayHasKey('timezone', $properties);
        $this->assertArrayHasKey('created', $properties);
        $this->assertArrayHasKey('last_updated', $properties);
    }

    /**
     * Requirement: Get a single object property from the model
     */
    public function testGetSingleObjectProperty()
    {
        // Confirm we can get the email property and that it is what it should be.
        $this->assertIsString($this->user->email);
        $this->assertEquals($this->user->email, self::USER_EMAIL);
    }

    /**
     * Requirement: Set object properties in bulk in the model
     */
    public function testSetBulkProperties()
    {
        $properties = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'city' => fake()->city()
        ];

        $this->user->assign($properties);
        
        $this->assertEquals($this->user->first_name, $properties['first_name']);
        $this->assertEquals($this->user->last_name, $properties['last_name']);
        $this->assertEquals($this->user->city, $properties['city']);
    }

    /**
     * Requirement: Set a single object property in the model
     */
    public function testSetSingleProperty()
    {
        $this->user->first_name = 'Foo';
        $this->user->last_name = 'Bar';

        $this->assertEquals($this->user->first_name, 'Foo');
        $this->assertEquals($this->user->last_name, 'Bar');
    }
    
    /**
     * Requirement: Validation of the object's properites
     */
    public function testValidateObjectProperties()
    {
        // Make sure we pass validation first.
        $this->assertTrue($this->user->validate());
        
        // Set an empty property to trigger failure on a "required" rule
        $firstName = $this->user->first_name;
        $this->user->first_name = null;
        $this->assertFalse($this->user->validate());
        $errors = $this->user->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('first_name', $errors);
        $this->assertIsString($errors['first_name'][0]);


        // Reset
        $this->user->first_name = $firstName;
        $this->assertTrue($this->user->validate());

        // Trigger failure of an "integer" rule
        $zip = $this->user->zip;
        $this->user->zip = 'abc';
        $this->assertFalse($this->user->validate());
        $errors = $this->user->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('zip', $errors);
        $this->assertIsString($errors['zip'][0]);

        // Reset
        $this->user->zip = $zip;
        $this->assertTrue($this->user->validate());


        // Reset and trigger a failure on a "max" rule
        $state = $this->user->state;
        $this->user->state = 'abc'; // max length = 2
        $this->assertFalse($this->user->validate());
        $errors = $this->user->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('state', $errors);
        $this->assertIsString($errors['state'][0]);

        // Reset
        $this->user->state = $state;
        $this->assertTrue($this->user->validate());
    }

    /**
     * Requirement: Fetch a row from the database that sets the model properties
     */
    public function testFetchRowFromDatabase()
    {
        // Save the model
        $this->assertTrue($this->user->save());
        $id = $this->user->id;
        $this->assertNotEmpty($id);

        // Load a new model using the PK
        $model = ManualUser::find($id);
        $this->assertInstanceOf(ManualUser::class, $model);
        $this->assertEquals($this->user->id, $model->id);
        $this->assertEquals($this->user->first_name, $model->first_name);
    }


    /**
     * Requirement: Create a row in the database from the properties in the model.
     */
    public function testCreateRowInDatabase()
    {
        // Save the model
        $this->assertTrue($this->user->save());
        $userId = $this->user->id;
        $this->assertNotEmpty($userId);
        
        // Load the user model
        $testModel = ManualUser::find($userId);
        $this->assertInstanceOf(ManualUser::class, $testModel);

        // Also load the raw row from the DB
        $row = DB::table('users')->where('id', $userId)->first();
        $this->assertNotEmpty($row);

        // Compare attributes to confirm the model has the values that are in the DB
        $this->assertEquals($testModel->first_name, $row->first_name);
        $this->assertEquals($testModel->last_name, $row->last_name);
    }

    /**
     * Requirement: Update a row in the database from the properties in the model.
     */
    public function testUpdateRowInDatabase()
    {
        // Save the model.
        $this->assertTrue($this->user->save());

        // Load the row in the DB. Compare value to the model.
        $dbRow = Db::table('users')->where('id', $this->user->id)->first();
        $this->assertEquals($this->user->first_name, $dbRow->first_name);
        
        // Update the model
        $this->user->first_name = 'Foo';
        $this->assertTrue($this->user->save());

        // Load the row in the DB again and compare value to the model.
        $dbRow = Db::table('users')->where('id', $this->user->id)->first();
        $this->assertEquals($dbRow->first_name, 'Foo');
    }

    /**
     * Requirement: Delete a row in the database from the properties in the model.
     */
    public function testDeleteRowInDatabase()
    {
        // Save the model.
        $this->assertTrue($this->user->save());

        // Load the row in the DB. Compare value to the model.
        $dbRow = Db::table('users')->where('id', $this->user->id)->first();
        $this->assertEquals($this->user->id, $dbRow->id);
        
        // Delete the model
        $this->assertTrue($this->user->delete());

        // Load the row in the DB again and confirm it's gone.
        $dbRow = Db::table('users')->where('id', $this->user->id)->first();
        $this->assertEmpty($dbRow);
    }

    

}
