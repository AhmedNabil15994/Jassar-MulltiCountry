<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait HasShemalessSettings
{
    /**
     * Get tenant setting.
     *
     * @param  string $key
     * @param  mixed $default_value
     * @return mixed
     */
    public function setting(string $key, $default_value = null)
    {
        $value = $this->extra_attributes->get('settings.' . $key, $default_value);

        if (! $value) {
            return $default_value;
        }

        return $value['is_secret']
            ? (
                $value['is_string']
                    ? Crypt::decryptString($value['data'])
                    : Crypt::decrypt($value['data'])
            )
            : $value['data'];
    }

    /**
     * Set tenant setting.
     *
     * @param  string       $key
     * @param  mixed       $value
     * @param  bool $encrypt
     * @return self
     */
    public function setSetting(string $key, $value, bool $encrypt = false)
    {
        $this->extra_attributes->set('settings.' . $key, [
            'is_secret' => $encrypt,
            'is_string' => is_string($value),
            'data' => $encrypt
                ? (
                    is_string($value)
                        ? Crypt::encryptString($value)
                        : Crypt::encrypt($value)
                )
                : $value,
        ]);

        return $this;
    }

    /**
     * Delete setting key and value.
     * @param  string       $key
     * @return self
     */
    public function deleteSetting(string $key)
    {
        $this->extra_attributes->forget('settings.' . $key);
        return $this;
    }
}
