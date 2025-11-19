<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;

trait HasEncrypt
{
    /**
     * Encrypt value using Laravel's secure encryption.
     */
    protected function encryptValue($value)
    {
        return encrypt($value);
    }

    /**
     * Safely decrypt value. If decryption fails, return original.
     */
    protected function decryptValue($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            // Value is either not encrypted or corrupted
            return $value;
        }
    }

    /**
     * Automatically encrypt fields before saving.
     */
    public function setAsttribute($key, $value)
    {
        if (isset($this->encryptable) && in_array($key, $this->encryptable)) {

            // Avoid encrypting already-encrypted values
            if (!empty($value) && !$this->isEncrypted($value)) {
                $value = $this->encryptValue($value);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Automatically decrypt fields when retrieving.
     */
    public function getAsttribute($key)
    {
        $value = parent::getAttribute($key);

        if (isset($this->encryptable) && in_array($key, $this->encryptable)) {
            return $this->decryptValue($value);
        }

        return $value;
    }

    /**
     * Ensure decrypted values appear in arrays / JSON api output.
     */
    public function toArray()
    {
        $array = parent::toArray();

        if (isset($this->encryptable)) {
            foreach ($this->encryptable as $key) {
                if (array_key_exists($key, $array)) {
                    $array[$key] = $this->decryptValue($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * Detect if a value looks like encrypted payload from Laravel.
     */
    protected function isEncrypted($value)
    {
        return is_string($value) && str_starts_with($value, 'eyJpdiI6');
    }
}
