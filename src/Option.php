<?php

namespace Corcel;

use Exception;

/**
 * Option class.
 *
 * @author José CI <josec89@gmail.com>
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Option extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'options';

    /**
     * @var string
     */
    protected $primaryKey = 'option_id';

    /**
     * @var array
     */
    protected $fillable = [
        'option_name',
        'option_value',
        'autoload',
    ];

    /**
     * @var array
     */
    protected $appends = ['value'];

    /**
     * @return mixed
     */
    public function getValueAttribute()
    {
        try {
            $value = unserialize($this->option_value);

            return $value === false &&
                $this->option_value !== false ? $this->option_value : $value;
        } catch (Exception $ex) {
            return $this->option_value;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Option
     */
    public static function add($key, $value)
    {
        return static::create([
            'option_name' => $key,
            'option_value' => is_array($value) ? serialize($value) : $value,
        ]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get($name)
    {
        if ($option = self::where('option_name', $name)->first()) {
            return $option->value;
        }

        return null;
    }

    /**
     * @return array
     * @deprecated
     */
    public static function getAll()
    {
        return static::asArray();
    }

    /**
     * @return array
     */
    public static function asArray()
    {
        return static::all()
            ->pluck('value', 'option_name')
            ->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this instanceof Option) {
            return [$this->option_name => $this->value];
        }

        return parent::toArray();
    }
}