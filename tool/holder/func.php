<?php
function is_administrator()
{
    // wp_get_current_user函数仅限在主题的functions.php中使用
    $currentUser = wp_get_current_user();
    if (!empty(@$currentUser->roles) && in_array('administrator',$currentUser->roles))
        return 1;  // 是管理员
    else
        return 0;  // 非管理员
}

function arCheckLogin()
{
    if (!is_administrator()) {
        echo "plz login";
        $url = 'https://' . @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'];
        //$url1 = 'http://'.@$_SERVER['HTTP_HOST'].@$_SERVER['PHP_SELF'].'?'.@$_SERVER['QUERY_STRING'];
        //wp_redirect('https://23ting.org/wp-login.php?loggedout=true');
        wp_redirect(
            add_query_arg(
                array(
                    //'update' => 'added',
                    //'id'     => $id,
                    'redirect_to' => urlencode($url)
                ),
                'wp-login.php'
            )
        );
        return;
    }
}

define('AR_DEBUG',false);

//$params1 = [
//    'dbUsername'=>'djfsjjsfas_icu',
//    'dbName'=>'djfsjjsfas_icu',
//    'dbPassword'=>'zc6pzwdQAK',
//    'username'=>'baizhan',
//    'password'=>'ZahSX9x*n5@F',
//    'catas'=>'a,b',
//];

function get_path($args){
    $basePath = dirname(__FILE__);
    return $basePath.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,$args);
}
$params1 = [
    'dbUsername' => 'cy753_com',
    'dbName' => 'cy753_com',
    'dbPassword' => 'jS8tGsymzN',
    'dbPrefix' => 'wp',
    'username' => 'ziliao1',
    'password' => '2&mn@4&GMsCx',
    'catas' => 'a,b',
    'mainServer' => 'https://cy753.com/',
    'dbUsernameFaka' => 'VfvgR4OE',
    'dbNameFaka' => 'sql_67_229_93_28',
    'dbPasswordFaka' => 'Nh9XsGsaLnvzQ8F5',
    'fakaUserId'=>'10021',
    'fakaUserName'=>'ziliao1',
];


function getCount($link,$sql,$key = 'goods_num')
{
    $countSqlRet = mysqli_query($link,$sql);
    if ($countSqlRet) {
        $countRecords = mysqli_fetch_all($countSqlRet,MYSQLI_ASSOC);
        $existNum = @$countRecords[0][$key] ? @$countRecords[0][$key] : 0;
    } else {
        $existNum = 0;
        //TODO ERROR
        //$blogRet[] = [
        //    $domain,'exist','error',mysqli_errno($link)
        //];
    }
    return $existNum;
}

function queryOne($link,$sql)
{
    $countSqlRet = mysqli_query($link,$sql);
    $countRecords = [];
    if ($countSqlRet) {
        $countRecords = mysqli_fetch_all($countSqlRet,MYSQLI_ASSOC);
        //$existNum = @$countRecords[0]['goods_num']?@$countRecords[0]['goods_num']:0;
    } else {
        //$existNum = 0;
        //TODO ERROR
        //$blogRet[] = [
        //    $domain,'exist','error',mysqli_errno($link)
        //];
    }
    return $countRecords ? @$countRecords[0] : null;
}

function queryAll($link,$sql)
{
    $countSqlRet = mysqli_query($link,$sql);
    //print_r($countSqlRet);
    $countRecords = [];
    if ($countSqlRet) {
        $countRecords = mysqli_fetch_all($countSqlRet,MYSQLI_ASSOC);
        //print_r($countRecords);
        //$existNum = @$countRecords[0]['goods_num']?@$countRecords[0]['goods_num']:0;
    } else {
        //print_r([
        //mysqli_errno($link)
        //]);
        //$existNum = 0;
        //TODO ERROR
        //$blogRet[] = [
        //    $domain,'exist','error',mysqli_errno($link)
        //];
    }
    return $countRecords ? $countRecords : [];
}

function runInsertSql($link,$sql)
{
    $insertSqlRet = mysqli_query($link,$sql);
    if (!$insertSqlRet) {
        return fail('insert fail',$sql);
    } else {
        return success(@$link->insert_id);
    }
}

function runDeleteSql($link,$sql)
{
    $insertSqlRet = mysqli_query($link,$sql);
    if (!$insertSqlRet) {
        return fail('deleted fail',$sql);
    } else {
        return success(@$link->insert_id);
    }
}
function runUpdateSql($link,$sql)
{
    $insertSqlRet = mysqli_query($link,$sql);
    if (!$insertSqlRet) {
        print_r([
            'ss'=> mysqli_error($link)
        ]);
        return fail('update fail',$sql);
    } else {
        return success('0');
    }
}
function prepareCmd($skip = false)
{
    ob_clean();
    header('Content-type: application/json');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0",false);
    header("Pragma: no-cache");
    set_time_limit(0);
    session_write_close();
    if(!$skip){
        arCheckLogin();
    }
}

function ret($ret = null,$data = null,$msg = null,$debug = null)
{
    return ([
        'ret' => $ret,
        'data' => $data,
        'msg' => $msg,
        'debug' => $debug
    ]);
}

function success($data = null,$debug = null)
{
    return ret('SUCCESS',$data,null,$debug);
}

function fail($data = null,$debug = null)
{
    return ret('FAIL',null,$data,$debug);
}
function isSuccess($ret)
{
    return $ret['ret'] == 'SUCCESS';
}
function getRandomStr1($len,$special = false)
{
    $chars = array(
        "a","b","c","d","e","f","g","h","i","j","k",
        "l","m","n","o","p","q","r","s","t","u","v",
        "w","x","y","z","A","B","C","D","E","F","G",
        "H","I","J","K","L","M","N","O","P","Q","R",
        "S","T","U","V","W","X","Y","Z","0","1","2",
        "3","4","5","6","7","8","9"
    );

    if ($special) {
        $chars = array_merge($chars,array(
            "!","@","#","$","?","|","{","/",":",";",
            "%","^","&","*","(",")","-","_","[","]",
            "}","<",">","~","+","=",",","."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        $str .= $chars[mt_rand(0,$charsLen)];    //随机取出一位
    }
    return $str;
}
function getStrFromList($len,$chars = []){
    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        $str .= $chars[mt_rand(0,$charsLen)];    //随机取出一位
    }
    return $str;
}
/**
 * @param $len
 *  mobile qq account goods number cdkey
 * @param string $case
 * @return string
 */

function genStr($len,$case = 'cdkey')
{
    $chars = [];
    $charsLower = array(
        "a","b","c","d","e","f","g","h","i","j","k",
        "l","m","n","o","p","q","r","s","t","u","v",
        "w","x","y","z"
    );
    $charsUpper = array(
        "A","B","C","D","E","F","G",
        "H","I","J","K","L","M","N","O","P","Q","R",
        "S","T","U","V","W","X","Y","Z"
    );
    $charsNumber = array(
        "0","1","2","3","4","5","6","7","8","9"
    );
    $charsNumberNoZero = array(
        "1","2","3","4","5","6","7","8","9"
    );
    $dxList = [
        '133','149','153','173','177','180','181','189','199'
    ];
    $ltList = [
        '130','131','132','145','155','156','166','171','175','176','185','186','166'
    ];
    $ydList = [
        '135','136','137','138','139','147','150','151','152','157','158','159','172','178','182','183','184','187','188','198'
    ];
    $charsMobilePrefix = [];
    $charsMobilePrefix = array_merge($charsMobilePrefix,$dxList);
    $charsMobilePrefix = array_merge($charsMobilePrefix,$ltList);
    $charsMobilePrefix = array_merge($charsMobilePrefix,$ydList);
    switch ($case) {
        case 'cdkey':
            $chars = array_merge($chars,$charsUpper);
            $chars = array_merge($chars,$charsLower);
            $chars = array_merge($chars,$charsNumber);
            //12
            return getStrFromList($len,$chars);
        case 'account':
        case 'goods':
            //标题
            //4-6
            $chars = array_merge($chars,$charsLower);
            $chars = array_merge($chars,$charsNumber);
            return getStrFromList($len,$chars);
        case 'num':
            return getStrFromList($len,$charsNumber);
        case 'ip':
            return implode('.',[
                getStrFromList(2,$charsNumberNoZero),
                getStrFromList(2,$charsNumberNoZero),
                getStrFromList(2,$charsNumberNoZero),
                getStrFromList(2,$charsNumberNoZero),
            ]);
        case 'qq':
            $chars = $charsNumber;
            return rand(1,9).getStrFromList($len-1,$chars);
        case 'mobile':
            return getStrFromList(1,$charsMobilePrefix).getStrFromList(8,$charsNumber);
    }
    return '';
}

function genQq(){
    //8-9
    return genStr(9,'qq');
}
function genMobile(){
    return genStr(9,'mobile');
}
function genGoods(){
    return genStr(rand(4,6),'goods');
}
function genAccount(){
    return genStr(rand(4,6),'goods');
}
function genCdKey(){
    return genStr(12,'cdkey');
}
function genContact(){
    return genStr(rand(6,10),'num');
}

function genIp(){
    return genStr(4,'ip');
}

function genGoodsCode(){
    return strtoupper(genStr(8,'cdkey'));
}

function noCacheHeader(){
    ob_clean();
    header('Content-type: application/json');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0",false);
    header("Pragma: no-cache");
    set_time_limit(0);
    session_write_close();
}

function getRandomStr($len, $special=false){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if($special){
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for($i=0; $i<$len; $i++){
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}

function get_value($params ,$k, $def = '' ){
    return @$params[$k]?$params[$k]:$def;
}
