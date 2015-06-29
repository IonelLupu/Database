<pre> 

<?php  
function pre($data){
	print_r($data);
	echo "<hr>";
}

include '../vendor/autoload.php';

use Webtron\Database\Connector\Connector as Connector;

use Webtron\Database\Query\Builder as DB;

$connector = new Connector([
	"database" => "app",
	"host"     => "localhost",
	"username" => "root",
	"password" => "root"
]);

DB::connect($connector);
pre(App\User::all());
die();
?>

<h2 style="text-align:center">SELECT Statement</h2>
<hr>
<hr>
<?php 

$posts = DB::table('users')
			->join('comments',function($join){
				$join->on('users.id','=','comments.user_id')
					 ->where('users.id','>',2);
			})
			->get(['users.username','comments.body']);
pre($posts);

$posts = DB::table('posts')
			->leftJoin(function($join){
				$join->table('users')->inner('comments','users.id','=','comments.user_id');
			},'posts.id','=','comments.post_id')
			->groupBy('users.email')
			->orderBy('posts.title')
			->limit(2,3)
			->get(['posts.title','comments.body','users.username','users.email']);
pre($posts);

$posts = DB::table('posts')
			->where('active',true)
			->where(function($query){
				$query->where('email','myEmail')
					  ->orWhere('username','myUser');
			})
			->get();
pre($posts);

$posts = DB::table('posts')
			->where('email','myEmail')
			->orWhere('username','myUser')
			->where('active',true)
			->get();
pre($posts);

// -------------------------------------------------------------------

$user = DB::table("users")->find(1);
pre($user);

// -------------------------------------------------------------------

// $users = DB::table("users")->insert([
// 		["username" => "test", "password" => md5("pass") , "email" => "test@example.com"],
// 		["username" => "test2", "password" => md5("pass2") , "email" => "test@example.com2"]
// 	]);
// pre($users);
// -------------------------------------------------------------------

$users = DB::table("users")
			->whereIn("id",function($query){
				$query->select(['id'])->whereBetween('id',[1,3]);
			})
			->whereNotIn("id",[1,2])
			->get();
pre($users);
// -------------------------------------------------------------------

$users = DB::table("users")
			->where('username','boyd')
			->orWhere(function($query){
				$query->where('id','>=',3)->where('username','LIKE',"e%");
			})
			->get(['username','id']);
pre($users);
// -------------------------------------------------------------------


$users = DB::table("users")
			->where('username',function($query){
				$query->select(["username"])->from("users")->where("id",1);
			})
			->get(['username']);
pre($users);
// -------------------------------------------------------------------



?>
</pre>


<h2 style="text-align:center">UPDATE Statement</h2>
<hr>
<hr>
<pre>
<?php  


// DB::table("users")->increment("credits");
// DB::table("users")->decrement("credits");

// DB::table("users")->update(["credits = votes"]);

DB::table("users")->where("username","test")->delete();

$users = DB::table("users")->get();
pre($users);


/****************************************************************
/****************************************************************
/****************************************************************
/****************************************************************
/****************************************************************
/****************************************************************
 * *********************  MODEL ********************
 */

use App\Car;
use App\Post;
use App\User;
use App\Role;
use App\Country;

// $users = User::all();
// pre($users);

// $user = User::find(1);
// pre($user);

// $users = User::where('credits','>',30)->limit(2)->get();
// pre($users);

// $user = new User;
// $user->email = 'roegn@example.com';
// $user->save();
// pre($user);

// $user = User::find($user->id);
// $user->delete();
// pre($user);

// $user = User::create([
// 		'email' => 'newEmail@example.com'
// 	]);
// $user->username = 'new User';
// $user->save();
// pre($user);


// $user = User::firstOrCreate(['email'=> 'snewEmail@example.com']);
// pre($user);

// $user = User::firstOrNew(['email'=> 'snewEmail@example.com']);
// pre($user);

// $user = User::findOrNew(145);
// pre($user);

// $user = User::destroy(32,37,38);
// pre($user);

// hasOne
// $car = User::find(1)->car;
// pre($car);

// hasMany
// $posts = User::find(1)->posts;
// pre($posts);

// belongsTo
// $user = Post::find(1)->user()->first();
// pre($user);

// hasManyThrough
$posts = Country::find(3)->posts;
pre($posts);


// manyToMany
$roles = User::find(2)->roles;
pre($roles);

$users = Role::where('name','Pro')->first()->users()->first();
pre( $users );
?>
</pre>