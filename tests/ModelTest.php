<?php

namespace Polass\Tests;

use InvalidArgumentException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Polass\Fluent\Model;

class ModelTest extends TestCase
{
    /**
     * テストに使う配列
     *
     * @var array
     */
    public $array = [ 'foo' => 'FOO', 'bar' => 'BAR' ];

    /**
     * テストに使う Arrayable を持つクラスのインスタンス
     *
     * @var \Illuminate\Contracts\Support\Arrayable
     */
    public $arrayable;

    /**
     * テストの準備
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->arrayable = new Collection($this->array);
    }

    /**
     * `__construct()` の正常系のテスト
     *
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Model::class, new Model);
        $this->assertInstanceOf(Model::class, new Model($this->array));
        $this->assertInstanceOf(Model::class, new Model($this->arrayable));
    }

    /**
     * `__construct()` の異常系のテスト
     *
     * @dataProvider provideInvalidAttributes
     */
    public function testConstructFailed($attributes)
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        $instance = new Model($attributes);
    }

    /**
     * `__construct()` と `fill()` の異常系のテストのためのデータプロバイダ
     *
     */
    public function provideInvalidAttributes()
    {
        return [
            [ 'string' ],
            [ 1 ],
        ];
    }

    /**
     * `hasSetMutator()` のテスト
     *
     */
    public function testHasSetMutator()
    {
        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasSetMutator('foo')
        );

        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasSetMutator('Foo')
        );

        $this->assertFalse(
            (new Stubs\ModelWithMutators)->hasSetMutator('baz')
        );
    }

    /**
     * `getSetMutator()` のテスト
     *
     */
    public function testGetSetMutator()
    {
        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('foo'),
            'setFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('Foo'),
            'setFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('fooBar'),
            'setFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('FooBar'),
            'setFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('foo_bar'),
            'setFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('foo-bar'),
            'setFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getSetMutator('foo123'),
            'setFoo123Attribute'
        );
    }

    /**
     * `hasGetMutator()` のテスト
     *
     */
    public function testHasGetMutator()
    {
        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasGetMutator('foo')
        );

        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasGetMutator('Foo')
        );

        $this->assertFalse(
            (new Stubs\ModelWithMutators)->hasGetMutator('baz')
        );
    }

    /**
     * `getGetMutator()` のテスト
     *
     */
    public function testGetGetMutator()
    {
        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('foo'),
            'getFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('Foo'),
            'getFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('fooBar'),
            'getFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('FooBar'),
            'getFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('foo_bar'),
            'getFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('foo-bar'),
            'getFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getGetMutator('foo123'),
            'getFoo123Attribute'
        );
    }

    /**
     * `hasHasMutator()` のテスト
     *
     */
    public function testHasHasMutator()
    {
        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasHasMutator('foo')
        );

        $this->assertTrue(
            (new Stubs\ModelWithMutators)->hasHasMutator('Foo')
        );

        $this->assertFalse(
            (new Stubs\ModelWithMutators)->hasHasMutator('baz')
        );
    }

    /**
     * `getHasMutator()` のテスト
     *
     */
    public function testGetHasMutator()
    {
        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('foo'),
            'hasFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('Foo'),
            'hasFooAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('fooBar'),
            'hasFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('FooBar'),
            'hasFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('foo_bar'),
            'hasFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('foo-bar'),
            'hasFooBarAttribute'
        );

        $this->assertEquals(
            (new Stubs\ModelWithMutators)->getHasMutator('foo123'),
            'hasFoo123Attribute'
        );
    }

    /**
     * `set()` と `__set()` のテスト
     *
     */
    public function testSet()
    {
        $instance = new Stubs\ModelWithMutators;


        $instance->set('foo', 'FOO');

        $this->assertArrayHasKey('foo', expose($instance)->attributes);
        $this->assertEquals('mutated `FOO`', expose($instance)->attributes['foo']);


        $instance->set('bar', 'BAR');

        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals('BAR', expose($instance)->attributes['bar']);


        $instance = new Stubs\ModelWithMutators;


        $instance['foo'] = 'FOO';

        $this->assertArrayHasKey('foo', expose($instance)->attributes);
        $this->assertEquals('mutated `FOO`', expose($instance)->attributes['foo']);


        $instance['bar'] = 'BAR';

        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals('BAR', expose($instance)->attributes['bar']);
    }

    /**
     * `fill()` の正常系のテスト
     *
     */
    public function testFill()
    {
        $instance = (new Stubs\ModelWithMutators)->fill($this->array);

        $this->assertArrayHasKey('foo', expose($instance)->attributes);
        $this->assertEquals('mutated `FOO`', expose($instance)->attributes['foo']);
        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals('BAR', expose($instance)->attributes['bar']);


        $instance = (new Stubs\ModelWithMutators)->fill($this->arrayable);

        $this->assertArrayHasKey('foo', expose($instance)->attributes);
        $this->assertEquals('mutated `FOO`', expose($instance)->attributes['foo']);
        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals('BAR', expose($instance)->attributes['bar']);
    }

    /**
     * `fill()` の異常系のテスト
     *
     * @dataProvider provideInvalidAttributes
     */
    public function testFillFailed($attributes)
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        (new Stubs\ModelWithMutators)->fill($attributes);
    }

    /**
     * `has()` と `__isset()` のテスト
     *
     */
    public function testHas()
    {
        $instance = new Stubs\ModelWithMutators;

        $this->assertTrue($instance->has('foo'));  // Has hasMutator
        $this->assertFalse($instance->has('bar'));
        $this->assertFalse($instance->has('baz'));

        $instance->fill($this->array);

        $this->assertTrue($instance->has('foo'));  // Has hasMutator
        $this->assertTrue($instance->has('bar'));
        $this->assertFalse($instance->has('baz'));


        $instance = new Stubs\ModelWithMutators;

        $this->assertTrue(isset($instance['foo']));  // Has hasMutator
        $this->assertFalse(isset($instance['bar']));
        $this->assertFalse(isset($instance['baz']));

        $instance->fill($this->array);

        $this->assertTrue(isset($instance['foo']));  // Has hasMutator
        $this->assertTrue(isset($instance['bar']));
        $this->assertFalse(isset($instance['baz']));
    }

    /**
     * `get()` のテスト
     *
     */
    public function testGet()
    {
        $instance = new Stubs\ModelWithMutators;

        $this->assertEquals('mutated ``', $instance->get('foo'));
        $this->assertNull($instance->get('bar'));

        $instance->fill($this->array);

        $this->assertEquals('mutated `mutated `FOO``', $instance->get('foo'));
        $this->assertEquals('BAR', $instance->get('bar'));
        $this->assertNull($instance->get('baz'));

        $this->assertEquals('BAZ', $instance->get('baz', 'BAZ'));
    }

    /**
     * `getAttributes()` のテスト
     *
     */
    public function testGetAttributes()
    {
        $instance = new Stubs\ModelWithMutators;

        $this->assertTrue(is_array($instance->getAttributes()));
        $this->assertEmpty($instance->getAttributes());

        $instance->fill($this->array);
        $attributes = $instance->getAttributes();

        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);
        $this->assertEquals('mutated `mutated `FOO``', $attributes['foo']);
        $this->assertEquals('BAR', $attributes['bar']);
    }

    /**
     * `toArray()` のテスト
     *
     */
    public function testToArray()
    {
        $instance = new Stubs\ModelWithMutators($this->array);

        $this->assertTrue(is_array($instance->toArray()), 'toArray()\'s result is not array');
        $this->assertEquals($instance->getAttributes(), $instance->toArray());
    }

    /**
     * `only()` のテスト
     *
     */
    public function testOnly()
    {
        $instance = new Stubs\ModelWithMutators($this->array);


        $attributes = $instance->only('foo');

        $this->assertTrue(is_array($attributes), 'only()\'s result is not array.');
        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->only('foo', 'bar');

        $this->assertTrue(is_array($attributes), 'only()\'s result is not array.');
        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->only([ 'foo', 'bar' ]);

        $this->assertTrue(is_array($attributes), 'only()\'s result is not array.');
        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->only('foo', 'baz');

        $this->assertTrue(is_array($attributes), 'only()\'s result is not array.');
        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->only('baz');

        $this->assertTrue(is_array($attributes), 'only()\'s result is not array.');
        $this->assertArrayNotHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);
    }

    /**
     * `except()` のテスト
     *
     */
    public function testExcept()
    {
        $instance = new Stubs\ModelWithMutators($this->array);


        $attributes = $instance->except('foo');

        $this->assertTrue(is_array($attributes), 'except()\'s result is not array.');
        $this->assertArrayNotHasKey('foo', $attributes);
        $this->assertArrayHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->except('foo', 'bar');

        $this->assertTrue(is_array($attributes), 'except()\'s result is not array.');
        $this->assertArrayNotHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->except([ 'foo', 'bar' ]);

        $this->assertTrue(is_array($attributes), 'except()\'s result is not array.');
        $this->assertArrayNotHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);


        $attributes = $instance->except('baz');

        $this->assertTrue(is_array($attributes), 'except()\'s result is not array.');
        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);
    }

    /**
     * `__call()` のテスト
     *
     */
    public function test__call()
    {
        $instance = new Stubs\ModelWithMutators($this->array);


        $instance->foo('FOO');

        $this->assertArrayHasKey('foo', expose($instance)->attributes);
        $this->assertEquals('mutated `FOO`', expose($instance)->attributes['foo']);


        $instance->bar('BAR');

        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals('BAR', expose($instance)->attributes['bar']);


        $instance->bar();

        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertNull(expose($instance)->attributes['bar']);


        $instance->bar('foo', 'bar');

        $this->assertArrayHasKey('bar', expose($instance)->attributes);
        $this->assertEquals([ 'foo', 'bar' ], expose($instance)->attributes['bar']);
    }

    /**
     * `__unset()` のテスト
     *
     */
    public function testUnset()
    {
        $instance = new Stubs\ModelWithMutators($this->array);

        $this->assertArrayHasKey('foo', expose($instance)->attributes);

        unset($instance['foo']);

        $this->assertArrayNotHasKey('foo', expose($instance)->attributes);
    }

    /**
     * `__toString()` のテスト
     *
     */
    public function testToString()
    {
        $instance = new Stubs\ModelWithMutators($this->array);

        $this->assertEquals($instance->toJson(), "$instance");
    }
}
