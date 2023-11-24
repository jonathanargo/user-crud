<?php

namespace App\Models;

use App\Exceptions\AttribtuesAlreadyLoadedException;
use App\Exceptions\AttributeDoesNotExistException;
use App\Helpers\AttributeValidator;
use Illuminate\Support\Facades\DB;

/**
 * Attribute hints for IDE convenience
 * 
 * @param string $id
 * @param string $first_name
 * @param string $last_name
 * @param string $email
 * @param string $mobile_number
 * @param string $address
 * @param string $city
 * @param string $state
 * @param string $zip
 * @param string $country
 * @param string $timezone
 * @param string $created
 * @param string $last_updated
 */
class ManualUser
{
    // Keeps track of whether our attributes have been loaded already.
    protected $loaded = false;

    /**
     * DB attributes.
     * 
     * I'm going to store these in an array rather than as class properties for easier manipulation
     * Normally bad practice, but this is what most ORMs do for that reason.
     */
    private $attributes = [
        'id' => '',
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'mobile_number' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => '',
        'timezone' => '',
        'created' => '',
        'last_updated' => '',
    ];

    /**
     * Attributes that can't be mass-assigned.
     * We don't want mass-assignment touching sensitive attributes like the ID or the timestamps
     */
    private $protectedAttributes = [
        'id',
        'created',
        'last_updated',
    ];

    // Validation errors
    protected $errors = [];

    /**
     * @param array<string, string> $attributes to be assigned on construction
     */
    public function __construct(array $attributes = [])
    {
        $this->assign($attributes);
    }

    /** 
     * Requirement: Get a single object property from the model
     * 
     * @param string $attribute
     */
    public function __get(string $attribute): string
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        // TODO JSA - Throw a custom exception here
        throw new AttributeDoesNotExistException($attribute);
    }

    /**
     * Requirement: Set a single object property on the model
     * 
     * @param string $attribute
     * @param string $value
     */
    public function __set(string $attribute, string $value)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            $this->attributes[$attribute] = $value;
        }

        throw new AttributeDoesNotExistException($attribute);
    }


    /**
     * Requirement: Set object properties in bulk in the model
     * 
     * Sets all mass-fillable attributes
     */
    public function assign(array $attributes): void
    {
        foreach ($attributes as $key => $val) {
            if (!in_array($key, $this->protectedAttributes)) { // Only non-protected attributes
                $this->attributes[$key] = $val;
            }
        }
    }

    /**
     * Requirement: Get all object properties from the model
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    protected function getValidationRules(): array
    {
        return [
            'id' => ['required', 'integer', 'on:update'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string'],
            'mobile_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string', 'max:2'],
            'zip' => ['required', 'string'],
            'country' => ['required', 'string'],
            'timezone' => ['string'],

            // These are MySQL datetime fields. 
            // In the interest of time, rather than implement custom validation for date time, I'm going to validate as generic strings
            'created' => ['required', 'string', 'on:update'],
            'last_updated' => ['string']
        ];
    }

    /**
     * Requirement: Validation of the object’s properties
     */
    protected function validate(): bool
    {
        $validator = new AttributeValidator();
        $result = $validator->validate($this->toArray(), $this->getValidationRules());
        $this->errors = $validator->getErrors();
        return $result;
    }

    /** Returns all model validation errors */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function loadById(int $id) {
        if ($this->loaded) {
            // We don't allow the user to load the attributes from the DB more than once.
            throw new AttribtuesAlreadyLoadedException();
        }

        $userRow = DB::table('users')->where('id', $id)->first();
        if ($userRow){
            // Directly assign attributes
            $this->attributes = (array)$userRow;
            return true;
        }
        return false;
    }

    public static function find(int $id): ManualUser|null
    {
        $user = new ManualUser();
        if ($user->loadById($id)) {
            return $user;
        }
        return null;
    }
}