<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value', 'description'];
    protected $table = 'setting__settings';

    /**
     * Get the value.
     *
     * @param  string  $value
     * @return string
     */
    public function getValue($value)
    {
        return unserialize($value);
    }

    /**
     * Set the value.
     *
     * @param  string  $value
     * @return string
     */
    public function setValue($value)
    {
        $this->attributes['value'] = serialize($value);
    }
}
