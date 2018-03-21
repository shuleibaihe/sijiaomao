<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 21:29
 */

/** 1、多维数组排序
 *  $items = array(
 *  array('http://www.abc.com/a/', 100, 120),
 *  array('http://www.abc.com/b/index.php', 50, 80),
 *  array('http://www.abc.com/a/index.html', 90, 100),
 *  array('http://www.abc.com/a/?id=12345', 200, 33),
 *  array('http://www.abc.com/c/index.html', 10, 20),
 *  array('http://www.abc.com/abc/', 10, 30)
 *  );
 *  变成如下的样子：
 *  array (
 *  array ( 'http://www.abc.com/a/', 390,253),
 *  array ('http://www.abc.com/b/', 50,80),
 *  array ('http://www.abc.com/c/', 10,20),
 *  array ('http://www.abc.com/abc/', 10,30)
 * )
 * print_r(getArr($items)) 运行结果如上
 * @return array
 */

function getArr($items){
    $map = [];
    foreach ($items as $keys => $item){
        $node = strrpos($item[0],'/')+1;  //计算斜杠节点
        $key = substr($item[0],0,$node);    //截取到斜杠钱 如：http://www.abc.com/a/
        if(isset($map[$key])){
            $map[$key][1] +=$item[1];
            $map[$key][2] +=$item[2];
        }else{
            $map[$key] = [$key,$item[1],$item[2]];
        }
    }
    return array_values($map); //以数字索引的形式返回数组
}

$items = array(
    array('http://www.abc.com/a/', 100, 120),
    array('http://www.abc.com/b/index.php', 50, 80),
    array('http://www.abc.com/a/index.html', 90, 100),
    array('http://www.abc.com/a/?id=12345', 200, 33),
    array('http://www.abc.com/c/index.html', 10, 20),
    array('http://www.abc.com/abc/', 10, 30)
);


/**
 * 2、一群猴子排成一圈，按1，2，...，n依次编号。然后从第1只开始数，数到第m只,把它踢出圈，
 * 从它后面再开始数，再数到第m只，在把它踢出去...，如此不停的进行下去，直到最后只剩下一只猴子为止，
 * 那只猴子就叫做大王。要求编程模拟此过程，输入m、n, 输出最后那个大王的编号
 *
 * echo getKing(10,3) 结果：4号为猴子大王
 *
 * @param $n
 * @param $m
 * @return mixed
 */
function getKing($n,$m){
    $arr = range(1,$n);
    $i = 0;
    while (count($arr)>1){
        if(($i+1)%$m == 0){
            unset($arr[$i]);
        }else{
            array_push($arr,$arr[$i]);
            unset($arr[$i]);
        }
        $i++;
    }
    return $arr[$i];
}


/**
 * 3、$commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
 * 实际的答案是：
 * $answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D'
 * 每题得分是5分，那么这个同学得分是多少？
 *
 * 转化数组取交集（正确的） 计算分数
 * echo getScore($commits,$answers); 测试分数：40分
 *
 * @param $commits
 * @param $answers
 * @return float|string
 */


function getScore($commits,$answers){
    if(!is_string($commits) || !is_string($answers)){
        return 'Commits and the answer must be a string!';
    }

    $coms = explode(',',$commits);
    $anws = explode(',',$answers);

    $single_score = 100/count($coms);
    $intersection = count(array_intersect_assoc($coms,$anws));

    return ceil($single_score*$intersection);
}

$commits= 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
$answers= 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D';




/**
 * 4、应用：使用php://input接收post提交的参数，从db中获取数据，
 * 并使用var_export写入文件缓存，下次访问从文件中获取数据。
 *
 * @param $file_cache
 */
function getPost($file_cache){
    $stream = file_get_contents("php://input");
    $standard = explode('&',$stream);
    $arr = [];
    foreach ($standard as $value){
        $tmpArr = explode('=',$value);
        $arr[$tmpArr[0]] = $tmpArr[1];
    }
    $str = '<?php retrun'.var_export($arr,true);
    file_put_contents("$file_cache",$str);
}


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
 * 6、有1000瓶水，其中有一瓶有毒，小白鼠只要尝一点带毒的水24小时后就会死亡，
 * 问至少要多少只小白鼠才能在24小时鉴别出哪瓶水有毒？
 *
 * echo getMinNum(1000)  测试结果为：需要 10 只小白鼠才能在24小时鉴别出哪瓶水有毒
 *
 * @param $num 瓶数
 * @return string
 */
function getMinNum($num){
    if(!is_numeric($num)) return 'The parameter must be the number';
    for ($i=1;$i<$num;$i++){
        if(pow(2,$i)-1 > 1000){
            return "需要 $i 只小白鼠才能在24小时鉴别出哪瓶水有毒";
        }
    }
}


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
 * getReversalArr($str) 测试结果为：n,m,l,j,i,h,g,f,e,d,c,b,a
 * @param $str
 * @return string
 */
function getReversalArr($str){
    if(!is_string($str))  return "Parameter must a string!";
    $arr = explode(',',$str);
    $tmp = [];
    foreach ($arr as $value){
        $val = array_pop($arr);
        array_push($tmp,$val);
    }
    return implode(',',$tmp);
}
$str = 'a,b,c,d,e,f,g,h,i,j,l,m,n';



/***
 * 9、从m个数中选出n个数来 ( 0 < n <= m) ，要求n个数之间不能有重复，其和等于一个定值k，
 * 求一段程序，罗列所有的可能。
 * 例如备选的数字是：11, 18, 12, 1, -2, 20, 8, 10, 7, 6 ，和k等于：18
 * getVal($nums) 结果：[18,][-2,20,][12,-2,8,][11,1,-2,8,][8,10,][11,7,][12,1,-2,7,][1,10,7,][12,6,][11,1,6,]
 * @param $nums
 */


function getVal($nums){
    for($i=0;$i<1024;$i++){
        $str = str_split(strrev(decbin($i)));
        $ans = 0;
        foreach($str as $k=>$v){
            $ans +=$v *$nums[$k];
        }
        if($ans==18) {
            echo '[';
            foreach($str as $k=>$v){
                if($v==1) {
                    echo $nums[$k];echo ",";
                }
            }
            echo "]";
        }
    }
}
$nums = [11,18,12,1,-2,20,8,10,7,6];




