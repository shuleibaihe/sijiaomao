<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17
 * Time: 15:12
 */
/**
 * 1、多维数组排序
$items = array(
array('http://www.abc.com/a/', 100, 120),
array('http://www.abc.com/b/index.php', 50, 80),
array('http://www.abc.com/a/index.html', 90, 100),
array('http://www.abc.com/a/?id=12345', 200, 33),
array('http://www.abc.com/c/index.html', 10, 20),
array('http://www.abc.com/abc/', 10, 30)
);
变成如下的样子：
array (
array ( 'http://www.abc.com/a/', 390,253),
array ('http://www.abc.com/b/', 50,80),
array ('http://www.abc.com/c/', 10,20),
array ('http://www.abc.com/abc/', 10,30)
)
*/
error_reporting( E_ALL&~E_NOTICE );

$items = array(
    array('http://www.abc.com/a/', 100, 120),
    array('http://www.abc.com/b/index.php', 50, 80),
    array('http://www.abc.com/a/index.html', 90, 100),
    array('http://www.abc.com/a/?id=12345', 200, 33),
    array('http://www.abc.com/c/index.html', 10, 20),
    array('http://www.abc.com/abc/', 10, 30)
);

$map = [];
foreach ($items as $item){
    $node = strrpos($item[0],'/')+1;
    $key = substr($item[0],0,$node);
    if(isset($map[$key])){
        $map[$key][1] +=$item[1];
        $map[$key][2] +=$item[2];
    }else{
        $map[$key] = [$key,$item[1],$item[2]];
    }
}
$map = array_values($map);


/**
 * 2、一群猴子排成一圈，按1，2，...，n依次编号。然后从第1只开始数，数到第m只,把它踢出圈，从它后面再开始数，
 * 再数到第m只，在把它踢出去...，如此不停的进行下去，直到最后只剩下一只猴子为止，那只猴子就叫做大王。要求编程模拟此过程，
 * 输入m、n, 输出最后那个大王的编号。function king($n, $m){ }
 */
function king($n,$m)
{
    for($i=1;$i<$n+1;$i++){
        $arr[] = $i;
    }
    $i=0;
    while (count($arr)>1){
        if(($i+1) % $m == 0){
            unset($arr[$i]);
        }else{
            array_push($arr,$arr[$i]);
            unset($arr[$i]);
        }
        $i++;
    }
    return $arr;
}

//var_dump(king(3,2));

/**
 * 3、$commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
 *实际的答案是：
 *$answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D'
 *每题得分是5分，那么这个同学得分是多少？
 */

$commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
$answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D';

$commits = explode(',',$commits);
$answers = explode(',',$answers);
$ans_count = count($answers);
if(is_array($commits) && is_array($answers)){
    $diff = array_diff_assoc($commits,$answers);
    $count = count($diff);
    $score = 100-(100/$ans_count)*$count;
}
//echo $score;



/**
 * 4、应用：使用php://input接收post提交的参数，从db中获取数据，并使用var_export写入文件缓存，下次访问从文件中获取数据。
 */
$stream = file_get_contents("php://input");
$standard = explode('&',$stream);
$arr = [];
foreach ($standard as $value){
    $tmpArr = explode('=',$value);
    $arr[$tmpArr[0]] = $tmpArr[1];
}
$str = '<?php retrun'.var_export($arr,true);
//file_put_contents('./cache.php',$str);

/**
 * 5、实现一个对象的数组式访问接口
 */
class Test implements ArrayAccess
{
    private $testData;

    public function offsetExists($key)
    {
        return isset($this->testData[$key]);
    }

    public function offsetSet($key, $value)
    {
        $this->testData[$key] = $value;
    }

    public function offsetGet($key)
    {
        return $this->testData[$key];
    }

    public function offsetUnset($key)
    {
        unset($this->testData[$key]);
    }
}

  $obj = new Test();

  //自动调用offsetSet方法
  $obj['data'] = 'data';

  //自动调用offsetExists
  if(isset($obj['data'])){
      //echo 'has setting!';
  }
  //自动调用offsetGet
  //var_dump($obj['data']);

  //自动调用offsetUnset
  unset($obj['data']);
  //var_dump($test['data']);


/**
 * 6、有1000瓶水，其中有一瓶有毒，小白鼠只要尝一点带毒的水24小时后就会死亡，问至少要多少只小白鼠才能在24小时鉴别出哪瓶水有毒？
 */
//10


/**
 *7、使用serialize序列化一个对象，并使用__sleep和__wakeup方法。
 */
class user {
    public $name;
    public $id;

    function __construct() {    // 给id成员赋一个uniq id
        $this->id = uniqid();
    }

    function __sleep() {       //此处不串行化id成员
        return(array('name'));
    }

    function __wakeup() {
        $this->id = uniqid();
    }
}

$u = new user();

$u->name = "Leo";

$s = serialize($u); //serialize串行化对象u，此处不串行化id属性，id值被抛弃

$u2 = unserialize($s); //unserialize反串行化，id值被重新赋值


/**
 * 8、利用数组栈实现翻转字符串功能
 */
$str = 'a,b,c,d,e,f,g,h,i,j,l,m,n';
$arr = explode(',',$str);
$tmp = [];
foreach ($arr as $k=>$v){
    $a = array_pop($arr);
    array_push($tmp,$a);
}
$tmp = implode(',',$tmp);
//var_dump($tmp);


/***
 * 从m个数中选出n个数来 ( 0 < n <= m) ，要求n个数之间不能有重复，其和等于一个定值k，
 * 求一段程序，罗列所有的可能。
 * 例如备选的数字是：11, 18, 12, 1, -2, 20, 8, 10, 7, 6 ，和k等于：18
 */




