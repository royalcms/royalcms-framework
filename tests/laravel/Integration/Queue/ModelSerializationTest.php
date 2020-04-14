<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\SerializesModels;
use LogicException;
use Orchestra\Testbench\TestCase;
use Schema;

/**
 * @group integration
 */
class ModelSerializationTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true');

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('database.connections.custom', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });

        Schema::connection('custom')->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
        });
    }

    public function testItSerializeUserOnDefaultConnection()
    {
        $user = ModelSerializationTestUser::create([
            'email' => 'mohamed@laravel.com',
        ]);

        ModelSerializationTestUser::create([
            'email' => 'taylor@laravel.com',
        ]);

        $serialized = serialize(new ModelSerializationTestClass($user));

        $unSerialized = unserialize($serialized);

        $this->assertSame('testbench', $unSerialized->user->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->user->email);

        $serialized = serialize(new CollectionSerializationTestClass(ModelSerializationTestUser::on('testbench')->get()));

        $unSerialized = unserialize($serialized);

        $this->assertSame('testbench', $unSerialized->users[0]->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->users[0]->email);
        $this->assertSame('testbench', $unSerialized->users[1]->getConnectionName());
        $this->assertSame('taylor@laravel.com', $unSerialized->users[1]->email);
    }

    public function testItSerializeUserOnDifferentConnection()
    {
        $user = ModelSerializationTestUser::on('custom')->create([
            'email' => 'mohamed@laravel.com',
        ]);

        ModelSerializationTestUser::on('custom')->create([
            'email' => 'taylor@laravel.com',
        ]);

        $serialized = serialize(new ModelSerializationTestClass($user));

        $unSerialized = unserialize($serialized);

        $this->assertSame('custom', $unSerialized->user->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->user->email);

        $serialized = serialize(new CollectionSerializationTestClass(ModelSerializationTestUser::on('custom')->get()));

        $unSerialized = unserialize($serialized);

        $this->assertSame('custom', $unSerialized->users[0]->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->users[0]->email);
        $this->assertSame('custom', $unSerialized->users[1]->getConnectionName());
        $this->assertSame('taylor@laravel.com', $unSerialized->users[1]->email);
    }

    public function testItFailsIfModelsOnMultiConnections()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Queueing collections with multiple model connections is not supported.');

        $user = ModelSerializationTestUser::on('custom')->create([
            'email' => 'mohamed@laravel.com',
        ]);

        $user2 = ModelSerializationTestUser::create([
            'email' => 'taylor@laravel.com',
        ]);

        $serialized = serialize(new CollectionSerializationTestClass(
            new Collection([$user, $user2])
        ));

        unserialize($serialized);
    }

    public function testItReloadsRelationships()
    {
        $order = tap(Order::create(), function (Order $order) {
            $order->wasRecentlyCreated = false;
        });

        $product1 = Product::create();
        $product2 = Product::create();

        Line::create(['order_id' => $order->id, 'product_id' => $product1->id]);
        Line::create(['order_id' => $order->id, 'product_id' => $product2->id]);

        $order->load('line', 'lines', 'products');

        $serialized = serialize(new ModelRelationSerializationTestClass($order));
        $unSerialized = unserialize($serialized);

        $this->assertEquals($unSerialized->order->getRelations(), $order->getRelations());
    }

    public function testItReloadsNestedRelationships()
    {
        $order = tap(Order::create(), function (Order $order) {
            $order->wasRecentlyCreated = false;
        });

        $product1 = Product::create();
        $product2 = Product::create();

        Line::create(['order_id' => $order->id, 'product_id' => $product1->id]);
        Line::create(['order_id' => $order->id, 'product_id' => $product2->id]);

        $order->load('line.product', 'lines', 'lines.product', 'products');

        $nestedSerialized = serialize(new ModelRelationSerializationTestClass($order));
        $nestedUnSerialized = unserialize($nestedSerialized);

        $this->assertEquals($nestedUnSerialized->order->getRelations(), $order->getRelations());
    }

    /**
     * Regression test for https://github.com/laravel/framework/issues/23068.
     */
    public function testItCanUnserializeNestedRelationshipsWithoutPivot()
    {
        $user = tap(User::create([
            'email' => 'taylor@laravel.com',
        ]), function (User $user) {
            $user->wasRecentlyCreated = false;
        });

        $role1 = Role::create();
        $role2 = Role::create();

        RoleUser::create(['user_id' => $user->id, 'role_id' => $role1->id]);
        RoleUser::create(['user_id' => $user->id, 'role_id' => $role2->id]);

        $user->roles->each(function ($role) {
            $role->pivot->load('user', 'role');
        });

        $serialized = serialize(new ModelSerializationTestClass($user));
        unserialize($serialized);
    }

    public function testItSerializesAnEmptyCollection()
    {
        $serialized = serialize(new CollectionSerializationTestClass(
            new Collection([])
        ));

        unserialize($serialized);
    }

    public function testItSerializesACollectionInCorrectOrder()
    {
        ModelSerializationTestUser::create(['email' => 'mohamed@laravel.com']);
        ModelSerializationTestUser::create(['email' => 'taylor@laravel.com']);

        $serialized = serialize(new CollectionSerializationTestClass(
            ModelSerializationTestUser::orderByDesc('email')->get()
        ));

        $unserialized = unserialize($serialized);

        $this->assertEquals($unserialized->users->first()->email, 'taylor@laravel.com');
        $this->assertEquals($unserialized->users->last()->email, 'mohamed@laravel.com');
    }

    public function testItCanUnserializeACollectionInCorrectOrderAndHandleDeletedModels()
    {
        ModelSerializationTestUser::create(['email' => '2@laravel.com']);
        ModelSerializationTestUser::create(['email' => '3@laravel.com']);
        ModelSerializationTestUser::create(['email' => '1@laravel.com']);

        $serialized = serialize(new CollectionSerializationTestClass(
            ModelSerializationTestUser::orderByDesc('email')->get()
        ));

        ModelSerializationTestUser::where(['email' => '2@laravel.com'])->delete();

        $unserialized = unserialize($serialized);

        $this->assertCount(2, $unserialized->users);

        $this->assertEquals($unserialized->users->first()->email, '3@laravel.com');
        $this->assertEquals($unserialized->users->last()->email, '1@laravel.com');
    }

    public function testItCanUnserializeCustomCollection()
    {
        ModelSerializationTestCustomUser::create(['email' => 'mohamed@laravel.com']);
        ModelSerializationTestCustomUser::create(['email' => 'taylor@laravel.com']);

        $serialized = serialize(new CollectionSerializationTestClass(
            ModelSerializationTestCustomUser::all()
        ));

        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(ModelSerializationTestCustomUserCollection::class, $unserialized->users);
    }

    public function testItSerializesTypedProperties()
    {
        if (version_compare(phpversion(), '7.4.0-dev', '<')) {
            $this->markTestSkipped('Typed properties are only available from PHP 7.4 and up.');
        }

        require_once __DIR__.'/typed-properties.php';

        $user = ModelSerializationTestUser::create([
            'email' => 'mohamed@laravel.com',
        ]);

        ModelSerializationTestUser::create([
            'email' => 'taylor@laravel.com',
        ]);

        $serialized = serialize(new TypedPropertyTestClass($user, 5, ['James', 'Taylor', 'Mohamed']));

        $unSerialized = unserialize($serialized);

        $this->assertSame('testbench', $unSerialized->user->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->user->email);
        $this->assertSame(5, $unSerialized->getId());
        $this->assertSame(['James', 'Taylor', 'Mohamed'], $unSerialized->getNames());

        $serialized = serialize(new TypedPropertyCollectionTestClass(ModelSerializationTestUser::on('testbench')->get()));

        $unSerialized = unserialize($serialized);

        $this->assertSame('testbench', $unSerialized->users[0]->getConnectionName());
        $this->assertSame('mohamed@laravel.com', $unSerialized->users[0]->email);
        $this->assertSame('testbench', $unSerialized->users[1]->getConnectionName());
        $this->assertSame('taylor@laravel.com', $unSerialized->users[1]->email);
    }

    public function test_model_serialization_structure()
    {
        $user = ModelSerializationTestUser::create([
            'email' => 'taylor@laravel.com',
        ]);

        $serialized = serialize(new ModelSerializationParentAccessibleTestClass($user, $user, $user));

        $this->assertSame(
            'O:78:"Illuminate\\Tests\\Integration\\Queue\\ModelSerializationParentAccessibleTestClass":2:{s:4:"user";O:45:"Illuminate\\Contracts\\Database\\ModelIdentifier":4:{s:5:"class";s:61:"Illuminate\\Tests\\Integration\\Queue\\ModelSerializationTestUser";s:2:"id";i:1;s:9:"relations";a:0:{}s:10:"connection";s:9:"testbench";}s:8:"'."\0".'*'."\0".'user2";O:45:"Illuminate\\Contracts\\Database\\ModelIdentifier":4:{s:5:"class";s:61:"Illuminate\\Tests\\Integration\\Queue\\ModelSerializationTestUser";s:2:"id";i:1;s:9:"relations";a:0:{}s:10:"connection";s:9:"testbench";}}', $serialized
        );
    }
}

class ModelSerializationTestUser extends Model
{
    public $table = 'users';
    public $guarded = ['id'];
    public $timestamps = false;
}

class ModelSerializationTestCustomUserCollection extends Collection
{
    //
}

class ModelSerializationTestCustomUser extends Model
{
    public $table = 'users';
    public $guarded = ['id'];
    public $timestamps = false;

    public function newCollection(array $models = [])
    {
        return new ModelSerializationTestCustomUserCollection($models);
    }
}

class Order extends Model
{
    public $guarded = ['id'];
    public $timestamps = false;

    public function line()
    {
        return $this->hasOne(Line::class);
    }

    public function lines()
    {
        return $this->hasMany(Line::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'lines');
    }
}

class Line extends Model
{
    public $guarded = ['id'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

class Product extends Model
{
    public $guarded = ['id'];
    public $timestamps = false;
}

class User extends Model
{
    public $guarded = ['id'];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->using(RoleUser::class);
    }
}

class Role extends Model
{
    public $guarded = ['id'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(RoleUser::class);
    }
}

class RoleUser extends Pivot
{
    public $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

class ModelSerializationTestClass
{
    use SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}

class ModelSerializationAccessibleTestClass
{
    use SerializesModels;

    public $user;
    protected $user2;
    private $user3;

    public function __construct($user, $user2, $user3)
    {
        $this->user = $user;
        $this->user2 = $user2;
        $this->user3 = $user3;
    }
}

class ModelSerializationParentAccessibleTestClass extends ModelSerializationAccessibleTestClass
{
    //
}

class ModelRelationSerializationTestClass
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
}

class CollectionSerializationTestClass
{
    use SerializesModels;

    public $users;

    public function __construct($users)
    {
        $this->users = $users;
    }
}
