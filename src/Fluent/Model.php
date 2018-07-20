<?php

namespace Polass\Fluent;

use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Model extends Fluent
{
    /**
     * コンストラクタ
     *
     * @param array $attributes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * フィールドが属性を持っているか
     *
     * @param string $key
     * @return bool
     */
    protected function hasAttributeInArray($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * フィールドから属性を取得
     *
     * @param string $key
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        if ($this->hasAttributeInArray($key)) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    /**
     * フィールドに属性を設定
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setAttributeInArray($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * フィールドから属性を削除
     *
     * @param string $key
     * @return void
     */
    protected function unsetAttributeInArray($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * フィールドから属性の名前を取得
     *
     * @return array
     */
    protected function getAttributeKeys()
    {
        return array_keys($this->attributes);
    }

    /**
     * 属性のミューテタが実装されているか
     *
     * @param string $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, $this->getSetMutator($key));
    }

    /**
     * 属性のミューテタの名前を取得
     *
     * @param string $key
     * @return string
     */
    public function getSetMutator($key)
    {
        return 'set' . Str::studly($key) . 'Attribute';
    }

    /**
     * 属性のゲッタが実装されているか
     *
     * @param string $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, $this->getGetMutator($key));
    }

    /**
     * 属性のゲッタの名前を取得
     *
     * @param string $key
     * @return string
     */
    public function getGetMutator($key)
    {
        return 'get' . Str::studly($key) . 'Attribute';
    }

    /**
     * 属性の有無を確認する関数が実装されているか
     *
     * @param string $key
     * @return bool
     */
    public function hasHasMutator($key)
    {
        return method_exists($this, $this->getHasMutator($key));
    }

    /**
     * 属性の有無を確認する関数の名前を取得
     *
     * @param string $key
     * @return string
     */
    public function getHasMutator($key)
    {
        return 'has' . Str::studly($key) . 'Attribute';
    }

    /**
     * 属性を設定
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = $this->getSetMutator($key);

            return $this->{$method}($value);
        }

        $this->setAttributeInArray($key, $value);

        return $this;
    }

    /**
     * 属性をまとめて設定
     *
     * @param mixed $values
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function fill($values)
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        } elseif (! is_array($values)) {
            throw new InvalidArgumentException('Argument must be Arrayable.');
        }

        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * 属性を持っているか
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if ($this->hasHasMutator($key)) {
            $method = $this->getHasMutator($key);

            return $this->{$method}($key);
        }

        return $this->get($key) !== null;
    }

    /**
     * 属性を取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->getAttributeFromArray($key);

        if ($this->hasGetMutator($key)) {
            $method = $this->getGetMutator($key);

            $value = $this->{$method}($value);
        }

        return isset($value) ? $value : $default;
    }

    /**
     * 属性の値を全て取得
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];

        foreach ($this->getAttributeKeys() as $key) {
            if (! in_array($key, $this->hidden ?? [], true)) {
                $attributes[$key] = $this->get($key);
            }
        }

        foreach ($this->visible ?? [] as $key) {
            $attributes[$key] = $this->get($key);
        }

        return $attributes;
    }

    /**
     * 配列に変換
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    /**
     * 指定したキーの属性のみ取得
     *
     * @param mixed $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return Arr::only($this->getAttributes(), $keys);
    }

    /**
     * 指定したキー以外の属性を取得
     *
     * @param mixed $keys
     * @return array
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return Arr::except($this->getAttributes(), $keys);
    }

    /**
     * 属性の値を関数呼び出しの形で設定
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if (($count = count($parameters)) === 1) {
            $parameters = $parameters[0];
        } elseif ($count === 0) {
            $parameters = null;
        }

        $this->set($method, $parameters);

        return $this;
    }

    /**
     * 属性の値をプロパティとして設定
     *
     * @param string $key
     * @return mixed
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * 属性を持っているか
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * 属性を削除
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->unsetAttributeInArray($key);
    }

    /**
     * インスタンスが持つ属性を文字列に変換
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
