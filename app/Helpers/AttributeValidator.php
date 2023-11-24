<?php

namespace App\Helpers;

use App\Exceptions\AttributeDoesNotExistException;

class AttributeValidator
{
    private $errors = [];
    /**
     * Validate a model attribute.
     * 
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $rules
     * @return bool
     */
    public function validate(array $attributes, array $rules, string $context = 'validate'): bool
    {
        foreach ($rules as $attribute => $ruleParts) {
            // Make sure attribute exists
            if (!array_key_exists($attribute, $attributes)) {
                throw new AttributeDoesNotExistException($attribute);
            }

            // Before we validate any of these, we need to check for an on:{context} rule
            // This isn't the most efficient way to do this but in the interest of time...
            foreach ($ruleParts as $rule) {
                $r = explode(':', $rule);
                // If this is an "on" rule and the context doesn't match the current context, skip this rule
                if (count($r) > 1 && $r[0] === 'on' && $r[1] !== $context) {
                    continue 2;
                }
            }

            // Now validate each rule
            foreach ($ruleParts as $rule) {
                $r = explode(':', $rule);

                if (count($r) === 1) {
                    $r[1] = null;
                }

                if ($r[0] === 'on') {
                    // These are meta-rules and don't need to be validated
                    continue;
                }

                $method = 'validate' . ucfirst($r[0]);

                if (!method_exists(self::class, $method)) {
                    // We'll throw this as a generic exception. This should stop execution.
                    throw new \Exception("Validation method {$method} does not exist.");
                }

                $this::$method($attribute, $attributes[$attribute], $r[1]);
            }
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function addError(string $attribute, string $error)
    {
        if (!array_key_exists($attribute, $this->errors)) {
            $this->errors[$attribute] = [];
        }

        $this->errors[$attribute][] = $error;
    }

    protected function validateRequired($attribute, $value, $options = null): bool
    {
        if (empty($value)) {
            $this->addError($attribute, "Value is required.");
        }

        return true;
    }

    protected function validateString($attribute, $value, $options = null)
    {
        if (!is_string($value) && !is_null($value)) {
            $this->addError($attribute, "Value must be a string.");
        }
    }

    protected function validateInteger($attribute, $value, $options = null)
    {
        if (!is_int($value) && !is_null($value)) {
            $this->addError($attribute, "Value must be an integer");
        }
    }

    protected function validateMax($attribute, $value, int $max)
    {
        if (intval($value) > $max) {
            $this->addError($attribute, "Value must be less than or equal to {$max}");
        }
    }
}