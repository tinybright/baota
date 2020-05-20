<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require(dirname(__FILE__) . '/wp-load.php');
require (dirname(__FILE__).'/func.php');
noCacheHeader();
//https://wp.yikhome.org/wp-debug.php?username=zhanzhang&password=Vw!nOoB7x@Xjs#BbF7&dbName=wordpress&dbUsername=wordpress&dbPassword=WVi1WQE7
//http://wp.yikhome.org/ar-set-so-options.php?username=zhanzhang&password=1&dbName=wordpress&dbUsername=wordpress&dbPassword=WVi1WQE7&catas=%E4%B8%80%E4%B8%AA%E5%88%86%E7%B1%BB,%E5%88%86%E7%B1%BB2
$str = @$_REQUEST['str'];
$scope = @$_REQUEST['scope'];

if($scope == 'shenma'){
    //"shenma-site-verification:a3371be5f8333897452ff0c167e76e15_1589194360"
    //"shenma-site-verification.txt"
    file_put_contents('shenma-site-verification.txt',"shenma-site-verification:".$str);
}else if($scope == 'a360'){
    //"shenma-site-verification:a3371be5f8333897452ff0c167e76e15_1589194360"
    //"shenma-site-verification.txt"
    file_put_contents($str.'.txt',$str);
}else if($scope == 'sougou'){
    # 57xfoLM9fR
    # sogousiteverification.txt
    file_put_contents('sogousiteverification'.'.txt',$str);
}else{
    //file_put_contents('baidu_verify_'.$str.'.html',$str);
}

#baidu_verify_CIrWqSmXnu.html
echo json_encode(success($str));
