<pre> 

<?php  
function pre($data){
	print_r($data);
	echo "<hr>";
}

include '../vendor/autoload.php';

use Webtron\Database\DB;


DB::connect([
	"database" => "app",
	"host"     => "localhost",
	"username" => "root",
	"password" => "root"
]);

DB::resultObject(false);
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

?>
</pre>