Database - Query builder
=====

## Instalation using composer
```php
{
    "require": {
        "webtron/database": "dev-master"
    }
}
```
## Set up
```php

use Webtron\Database\Connector\Connector as Connector;

use Webtron\Database\Query\Builder as DB;

$connector = new Connector([
	"database" => "app",
	"host"     => "localhost",
	"username" => "root",
	"password" => "root"
]);

DB::connect($connector);
```

### Get all rows from a table
```php
$users = DB::table("users")->get();
print_r($users);
```
###### SQL Output
```SQL
SELECT * FROM users  
```

### Using Where Clause
```php
$users = DB::table("users")->where('username','Mark')->get();
print_r($users);
```
###### SQL Output
```SQL
SELECT * FROM users WHERE username = 'Mark' 
```


### Using nested parameter groupings
```php
$users = DB::table("users")
			->where('username','Mark')
			->orWhere(function($query){
				$query->where('id','>',3)->where('username','LIKE',"J%");
			})
			->get(['username']);
print_r($users);
```
###### SQL Output
```SQL
SELECT username FROM users WHERE username = 'Mark' OR ( id > '3' AND username LIKE 'J%' ) 
```

### Using Subqueries
```php
$users = DB::table("users")
			->where('username',function($query){
				$query->select(["username"])->from("list")->where("id",1);
			})
			->get(['username']);
print_r($users);
```
###### SQL Output
```SQL

SELECT username FROM users WHERE username = ( SELECT username FROM list WHERE id = '1' ) 
```


### Using Join Clause
```php
$posts = DB::table('users')
			->join('comments',function($join){
				$join->on('users.id','=','comments.user_id')
					 ->where('users.id','>',2);
			})
			->get(['users.username','comments.body']);
print_r($posts);
```
###### SQL Output
```SQL

SELECT users.username, comments.body FROM users INNER JOIN comments ON users.id = comments.user_id AND users.id > 2
```

### Using the Model
```php

use Webtron\Database\Model\Model;

class User extends Model {

	protected $table = 'users';
}
```
```php

$users = User::all();
print_r($users);
```


