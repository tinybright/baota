<?php
//require(dirname(__FILE__) . '/wp-load.php');
require (dirname(__FILE__).'/func.php');
require (dirname(__FILE__).'/BtApi.php');

noCacheHeader();
$action = $_REQUEST['action'];
#http://127.0.0.1/holder.php?action=get_wp_config&dbName=wp1_com&dbUsername=wp1_com&dbPassword=datapassword1&domain=wp1.com
#php 版本
global $btUrl;
$btUrl  = 'http://localhost:8888';
global $btKey;
$btKey = 'Pjy3ehPjuvwyYNBv1VZb3ycypa7yDFyl';
#ps -ef|grep BT |grep -v grep|awk '{print $2}'|xargs kill -9 && /usr/bin/python /www/server/panel/BT-Panel
#http://wp10.com:81/holder.php?action=add_site&domains=wp1.com,wp2.com,wp3.com
function add_site($json){
    global $btUrl,$btKey;
    $params = json_decode($json,true);
    $domainStr = get_value($params,'domains','');
    if(!$domainStr){
        return fail('域名不能为空');
    }
    $domains = explode(',',$domainStr);
    $datapassword = get_value($params,'datapassword1','datapassword1');
    $domains0 = @$domains[0];
    $siteName = $domains0;
    //$btUrl = 'http://104.168.134.97:8888';
    //$btUrl = 'http://127.0.0.1:8888';
    //$btKey = 'Pjy3ehPjuvwyYNBv1VZb3ycypa7yDFyl';
    $btApi = new BtApi($btUrl,$btKey);
        $url = $btApi->BT_PANEL.'/site?action=AddSite';
        /**
         *
         * [webname_1] => test1.com
        [ps] => test1.com
        [path] => /www/wwwroot/test1.com
        [datauser] => test1_com
        [datapassword] => KBEjSNrshR
        [version] => 73
        [port] => 80
        [webname] => {"domain":"test1.com","domainlist":[],"count":1}
        [ftp] => false
        [sql] => true
        [address] => localhost
        [codeing] => utf8
         */
        //准备POST数据
        $p_data = $btApi->GetKeyData();		//取签名
        //$p_data['id'] = '';
        $p_data['webname_1'] = implode("\r\n",$domains);
        //$p_data['domain'] = $siteName;
        $p_data['ps'] = $siteName.'';
        $p_data['path'] = '/www/wwwroot/'.$siteName;
        $p_data['datauser'] = str_replace('.','_',$siteName);
        $p_data['datapassword'] = 'datapassword1';
        $p_data['version'] = '73';
        $p_data['port'] = '80';
        $domains1 = [];

        foreach($domains as $k=>$v){
            if($k){
                $domains1[] = $v;
            }
        }
        $p_data['webname'] = json_encode([
            'domain'=>$siteName,
            'domainlist'=>$domains1,
            "count"=>count($domains)
        ]);
        $p_data['ftp'] = 'false';
        $p_data['sql'] = 'true';
        $p_data['address'] = 'localhost';
        $p_data['codeing'] = 'utf8mb4';
        //$p_data['codeing'] = 'utf8';
        //webname_1=wp3.com%0D%0Awp4.com%0D%0Awp5.com&ps=wp3.com&path=%2Fwww%2Fwwwroot%2Fwp3.com&datauser=wp3_com&datapassword=ZjiEy87Hfd&version=73&port=80&webname={"domain":"wp3.com","domainlist":["wp4.com","wp5.com"],"count":3}&ftp=false&sql=true&address=localhost&codeing=utf8&version=73
        //        (
        //        [ftpStatus] =>
        //    [databaseUser] => _
        //        [databaseStatus] => 1
        //    [databasePass] => datapassword
        //        [siteId] => 2
        //    [siteStatus] => 1
        //)
        //$p_data['webname'] = $siteName;
        //$p_data['domain'] = $domain;

        //请求面板接口

        //        (
        //        [status] =>
        //    [msg] => IP校验失败,您的访问IP为[172.17.0.1]
        //)
        //        [status] =>
        //[msg] => 您添加的站点已存在!
        //(
        //    [ftpStatus] =>
        //    [databaseUser] => wp1_com
        //    [databaseStatus] => 1
        //    [databasePass] => datapassword
        //    [siteId] => 2
        //    [siteStatus] => 1
        //)
        //webname_1=wp3.com%0D%0Awp4.com%0D%0Awp5.com&ps=wp3.com&path=%2Fwww%2Fwwwroot%2Fwp3.com&datauser=wp3_com&datapassword=ZjiEy87Hfd&version=73&port=80&webname={"domain":"wp3.com","domainlist":["wp4.com","wp5.com"],"count":3}&ftp=false&sql=true&address=localhost&codeing=utf8&version=73


        //print_r($p_data);
        $result = $btApi->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);

        if(@$data['siteId']){
            return success($data['siteId'],$data);
        }
        if(!@$data['msg']){
            return fail($data['msg']);
        }

        return fail('add fail');
}
#http://wp10.com:81/holder.php?action=install_wp&siteName=wp1.com
function install_wp($json){
    //echo "??1";
    global $btUrl,$btKey;
    set_time_limit(0);
    $params = json_decode($json,true);
    $siteName = get_value($params,'siteName','');
    if(!$siteName){
        return fail('网站名不能为空');
    }
    //echo "??2";
#curl -I http://127.0.0.1:8888/deployment?action=SetupPackage -X POST -d 'request_token=d416a49ef256ee18433a0a892be2d4ca&request_time=1589880082&dname=wordpress&site_name=wp1.com&php_version=73'
    //[request_token] => d416a49ef256ee18433a0a892be2d4ca
    //[request_time] => 1589880082
    //[request_ignore] => 1
    //[dname] => wordpress
    //[site_name] => wp1.com
    //[php_version] => 73
    //dname: wordpress
        //site_name: wp3.com
        //php_version: 73
        $btApi = new BtApi($btUrl,$btKey);
        $url = $btApi->BT_PANEL.'/deployment?action=SetupPackage';
        $p_data = $btApi->GetKeyData();		//取签名
        $p_data['dname'] = 'wordpress';
        //找网站
        $p_data['site_name'] = $siteName;
        $p_data['php_version'] = '73';
        //print_r($p_data);
        //echo 3;
        //return;
        $result = $btApi->HttpPostCookie($url,$p_data);
        print_r([
            $url,json_encode($p_data),$result
        ]);
    //dname=wordpress&site_name=wp5.com&php_version=73
    //    ob_clean();
        return "";
        $output = $result;
        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, ($p_data));
        ////curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        ////curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        ////curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //$output = curl_exec($ch);
        //curl_close($ch);
        //$url = 'echo 33 && curl -I http://127.0.0.1:8888/deployment?action=SetupPackage -X POST -d \'request_token='.$p_data['request_token'].'&request_time='.$p_data['request_time'].'&dname=wordpress&site_name=wp1.com&php_version=73\' && echo 44';
        //exec('echo 33 && curl -I http://127.0.0.1:8888/deployment?action=SetupPackage -X POST -d \'request_token='.$p_data['request_token'].'&request_time='.$p_data['request_time'].'&dname=wordpress&site_name=wp1.com&php_version=73\' && echo 44',$result1);

        print_r(['dd'=>$output]);
        //print_r(['dd1'=>$result1]);
        //解析JSON数据
        return success('hello',$output);

        //$data = json_decode($result,true);
        //{"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
        /**
         * {"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
         */
        //'http://localhost:8888/deployment?action=SetupPackage';
        //dname=wordpress&site_name=test3.com&php_version=73
        //{"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
//    Array
//    (
//        [request_token] => 39c08a5f894ceaebc3dff8c9967d7750
//    [request_time] => 1589878516
//    [request_ignore] => 1
//    [dname] => wordpress
//    [site_name] => wp1.com
//    [php_version] => 73
//)
//{"ret":"SUCCESS","data":null,"msg":"deploy fail","debug":null}
    $status = get_value($data,'status');
    $msg = get_value($data,'msg');
    if($status){
        return success($status,$data);
    }
    if($msg){
        return fail($msg,$data);
    }
    return fail('deploy fail',$data);
}

function setup_wp_config($json){

    //$url = 'http://127.0.0.1/holder.php?action=get_wp_config&dbName=wp1_com&dbUsername=wp1_com&dbPassword=datapassword1&domain=wp1.com';
    //$result = $btApi->HttpPostCookie($url,[]);
    //print_r([$result]);
    //return;
    //public function actionGetWpConfig($dbName = '',$dbUsername= '',$dbPassword= '',$domain = ''){
    $params = json_decode($json,true);
    $dbName = @$params['dbName'];
    $dbUsername = @$params['dbUsername'];
    $dbPassword = @$params['dbPassword'];
    $domain = @$params['domain'];
        $content = file_get_contents(get_path(['wordpress','wp-config-sample.php']));
        //database_name_here
        //username_here
        //password_here
        //secret_key0 -7
        //$dbName = 'wordpress';
        //$dbUsername = 'wordpress';
        //$dbPassword = 'aUwDOTMs';
        //$dbPassword = 'WVi1WQE7';
        // 数据库名：wordpress

        // 用户：wordpress

        // 密码：aUwDOTMs

        // 访问站点：http://ip/index.php

        $infos = [];
        $infos['database_name_here'] = $dbName;
        $infos['username_here'] = $dbUsername;
        $infos['password_here'] = $dbPassword;
        $infos['mydomain'] = $domain;

        $secretNum = 8;
        function str_replace_limit($search, $replace, $subject, $limit=-1){
            if(is_array($search)){
                foreach($search as $k=>$v){
                    $search[$k] = '`'. preg_quote($search[$k], '`'). '`';
                }
            }else{
                $search = '`'. preg_quote($search, '`'). '`';
            }
            return preg_replace($search, $replace, $subject, $limit);
        }
        for($i = 0;$i<$secretNum;$i++){
            $randStr = getRandomStr(64,true);
            //$infos['secret_key'.$i] = getRandomStr(64,true);
            $content = str_replace_limit('put your unique phrase here',$randStr,$content,1);
        }


        foreach ($infos as $infoKey=>$infoValue){
            $content = str_replace($infoKey,$infoValue,$content);
        }

        file_put_contents('/www/wwwroot/'.$domain.'/wp-config.php',$content);
        return success('');

        $fileName = 'wp-config.php';
        ob_end_clean();
        //header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$fileName.'');
        header('Cache-Control: max-age=0');
        //header('location:'.$url);
        //readfile($url);
        echo $content;

        return;

        //secret_key0
        //$content = str_replace('')

    //}
}

function setup_wp($json){
    global $btUrl,$btKey;
#http://wp10.com:81/holder.php?action=setup_wp&title=title1&email=email@qq.com&username=username1&domain=wp1.com&adminPassword=sssjkskskll1j23lj1
    $params = json_decode($json,true);
    $adminPassword = get_value($params,'adminPassword','sssss11sda231asdasd2');
    $title = get_value($params,'title','weblog_title1');
    $username = get_value($params,'username','user_name1');
    $email = get_value($params,'email','test3@qq.com');
    $domain = get_value($params,'domain','wp1.com');

    $btApi = new BtApi($btUrl,$btKey);
    $url = 'http://'.$domain.'/wp-admin/install.php?step=2';
    $p_data = [
        //'step'=>'2',
        'weblog_title'=>$title,
        'user_name'=>$username,
        'admin_password'=>$adminPassword,
        'admin_password2'=>$adminPassword,
        'admin_email'=>$email,
        'blog_public'=>1,
        'language'=>'zh_CN'
    ];
    $result = $btApi->HttpPostCookie($url,$p_data);

    return success($result,[$p_data,$url]);
}

function set_token($json){
    $btKey = '';
    //UibotUtil::$key = 'JpOFzwd76tZNcVFWe6sPh7RL5hUqCuvY';
    $btApi = new BtApi('http://192.168.15.89:8888',$btKey);
    $params = json_decode($json,true);
    $url = $btApi->BT_PANEL.'/config1?action=set_token_';
    $p_data = $btApi->GetKeyData();		//取签名
    $p_data['t_type'] = '1';
    $result = $btApi->HttpPostCookie($url,$p_data);
    //解析JSON数据
    $data = json_decode($result,true);
    return success($data);
}

function actionTest1(){

    //$datas = urldecode("webname_1=test1.com&ps=test1.com&path=%2Fwww%2Fwwwroot%2Ftest1.com&datauser=test1_com&datapassword=KBEjSNrshR&version=73&port=80&webname={\"domain\":\"test1.com\",\"domainlist\":[],\"count\":1}&ftp=false&sql=true&address=localhost&codeing=utf8&version=73");
    //
    //$parts = explode("&",$datas);
    //
    //$info = [];
    //foreach ($parts as $part){
    //    $kv = explode("=",$part);
    //    $info[@$kv[0]] = @$kv[1]?$kv[1]:'';
    //}

    //TplUtil::prepareOutput();
    //print_r($info);
    //return;

    $params = $_GET;
    $data = @$params['data'];
    $data = 'test2.com';
    $domains = [$data];
    $domains0 = @$domains[0];
    $siteName = $domains0;
    //$host = CheckUtil::getValue($params,'host','local');
    //$data = 'wp.zggb999.org,wp.zhainanyouhuo.com,wp.zhaoweisuliao.org,wp.zmcqww.org,wp.zzydx.com';
    //
    //
    UibotUtil::$key = '1234';
    UibotUtil::$key = 'JpOFzwd76tZNcVFWe6sPh7RL5hUqCuvY';
    $btApi = new BtApi('http://192.168.15.89:8888',UibotUtil::$key);
    $actions = 'SetupPackage';
    $step = 1;
    //http://test3.com/wp-admin/install.php?step=1&language=zh_CN

    if($step == 2){
        //$weblog_title         = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
        //$user_name            = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
        //$admin_password       = isset( $_POST['admin_password'] ) ? wp_unslash( $_POST['admin_password'] ) : '';
        //$admin_password_check = isset( $_POST['admin_password2'] ) ? wp_unslash( $_POST['admin_password2'] ) : '';
        //$admin_email          = isset( $_POST['admin_email'] ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';
        //$public               = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : 1;

        $ret = NetUtil::post('http://test3.com/wp-admin/install.php?step=2',[
            //'step'=>'2',
            'weblog_title'=>'weblog_title3',
            'user_name'=>'user_name3',
            'admin_password'=>'sssss11sda231asdasd2',
            'admin_password2'=>'sssss11sda231asdasd2',
            'admin_email'=>'test3@qq.com',
            'blog_public'=>1,
            'language'=>'zh_CN'
        ]);

        //weblog_title: weblog_title
        //user_name: user_name
        //admin_password: sssss11sda231asdasd2
        //admin_password2: sssss11sda231asdasd2
        //admin_email: test3@qq.com
        //Submit: 安装WordPress
        //language: zh_CN

        print_r([
            'ret'=>$ret
        ]);
        #WordPress安装完成。谢谢！
        /**
         * <h1>成功！</h1>

        <p>WordPress安装完成。谢谢！</p>

        <table class="form-table install-success">
        <tr>
        <th>用户名</th>
        <td>user_name3</td>
        </tr>
        <tr>
        <th>密码</th>
        <td>
        <p><em>您设定的密码。</em></p>
        </td>
        </tr>
        </table>
         */
        return;
    }
    if($step == 3){
        $btApi = new BtApi('http://192.168.15.89:8888',UibotUtil::$key);
        $url = $btApi->BT_PANEL.'/deployment?action=SetupPackage';
        $p_data = $btApi->GetKeyData();		//取签名
        $p_data['dname'] = 'wordpress';
        //找网站
        $p_data['site_name'] = 'test9.com';
        $p_data['php_version'] = '73';
        $result = $btApi->HttpPostCookie($url,$p_data);
        //解析JSON数据
        $data = json_decode($result,true);
        TplUtil::prepareOutput();
        print_r($result);

        print_r($data);
        /**
         * {"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
         */
        return;
        //'http://localhost:8888/deployment?action=SetupPackage';
        //dname=wordpress&site_name=test3.com&php_version=73
        //{"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
    }
    if($step == 1 ){
        $url = $btApi->BT_PANEL.'/config1?action=set_token_';
        /**
         *
         * [webname_1] => test1.com
        [ps] => test1.com
        [path] => /www/wwwroot/test1.com
        [datauser] => test1_com
        [datapassword] => KBEjSNrshR
        [version] => 73
        [port] => 80
        [webname] => {"domain":"test1.com","domainlist":[],"count":1}
        [ftp] => false
        [sql] => true
        [address] => localhost
        [codeing] => utf8
         */
        //准备POST数据
        $p_data = $btApi->GetKeyData();		//取签名
        $p_data['t_type'] = '1';
        //$p_data['webname_1'] = $siteName;
        //$p_data['ps'] = $siteName;
        //$p_data['path'] = '/www/wwwroot/'.$siteName;
        //$p_data['datauser'] = str_replace($siteName,'.','_');
        //$p_data['datapassword'] = 'datapassword';
        //$p_data['version'] = '73';
        //$p_data['port'] = '80';
        //$p_data['webname'] = json_encode([
        //    'domain'=>$siteName,
        //    'domainlist'=>[],
        //    "count"=>1
        //]);
        //$p_data['ftp'] = 'false';
        //$p_data['sql'] = 'true';
        //$p_data['address'] = 'localhost';
        //$p_data['codeing'] = 'utf8';


        //$p_data['webname'] = $siteName;
        //$p_data['domain'] = $domain;

        //请求面板接口

        //        (
        //        [status] =>
        //    [msg] => IP校验失败,您的访问IP为[172.17.0.1]
        //)
        $result = $btApi->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);

        TplUtil::prepareOutput();
        print_r($result);

        print_r($data);
        return;
    }


    $url = $btApi->BT_PANEL.'/site?action=AddDomain';
    $url = $btApi->BT_PANEL.'/site?action=AddSite';
    /**
     *
     * [webname_1] => test1.com
    [ps] => test1.com
    [path] => /www/wwwroot/test1.com
    [datauser] => test1_com
    [datapassword] => KBEjSNrshR
    [version] => 73
    [port] => 80
    [webname] => {"domain":"test1.com","domainlist":[],"count":1}
    [ftp] => false
    [sql] => true
    [address] => localhost
    [codeing] => utf8
     */
    //准备POST数据
    $p_data = $btApi->GetKeyData();		//取签名
    //$p_data['id'] = '';
    $p_data['webname_1'] = $siteName;
    //$p_data['domain'] = $siteName;
    $p_data['ps'] = $siteName;
    $p_data['path'] = '/www/wwwroot/'.$siteName;
    $p_data['datauser'] = str_replace($siteName,'.','_');
    $p_data['datapassword'] = 'datapassword';
    $p_data['version'] = '73';
    $p_data['port'] = '80';
    $p_data['webname'] = json_encode([
        'domain'=>$siteName,
        'domainlist'=>[$siteName],
        "count"=>1
    ]);
    $p_data['ftp'] = 'false';
    $p_data['sql'] = 'true';
    $p_data['address'] = 'localhost';
    $p_data['codeing'] = 'utf8';

    //        (
    //        [ftpStatus] =>
    //    [databaseUser] => _
    //        [databaseStatus] => 1
    //    [databasePass] => datapassword
    //        [siteId] => 2
    //    [siteStatus] => 1
    //)
    //$p_data['webname'] = $siteName;
    //$p_data['domain'] = $domain;

    //请求面板接口

    //        (
    //        [status] =>
    //    [msg] => IP校验失败,您的访问IP为[172.17.0.1]
    //)
    print_r($p_data);
    $result = $btApi->HttpPostCookie($url,$p_data);

    //解析JSON数据
    $data = json_decode($result,true);

    TplUtil::prepareOutput();
    print_r($result);

    print_r($data);
    return;
    $ret = $btApi->getSite();
    //print_r($ret);
    $domain =
    $sample = "    [data] => Array
        (
            [0] => Array
                (
                    [status] => 1
                    [ps] => wp.com
                    [domain] => 2
                    [name] => wp.com
                    [addtime] => 2020-03-07 19:34:51
                    [path] => /www/wwwroot/wp
                    [backup_count] => 0
                    [edate] => 0000-00-00
                    [id] => 3
                )

        )
";


    $id = '';
    $siteName = '';
    if(@$ret['data']){
        $id = @$ret['data'][0]['id'];
        $siteName = @$ret['data'][0]['name'];
    }
    if(!($id && $siteName)){
        //TplUtil::echoFail('获取网站信息失败',[$id,$siteName]);
        TplUtil::echoSuccess(['获取网站信息失败']);
        return;
    }
    $domains = explode(',',$data);
    $retList = [];
    foreach($domains as $domain){
        //$ret = $btApi->addDomain($id,$siteName,$domain);

        $url = $btApi->BT_PANEL.'/site?action=AddDomain';

        //准备POST数据
        $p_data = $btApi->GetKeyData();		//取签名
        $p_data['id'] = $id;
        $p_data['webname'] = $siteName;
        $p_data['domain'] = $domain;

        //请求面板接口
        $result = $btApi->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);
        return $data;

        $status = @$ret['status'];
        $msg = @$ret['msg'];
        //print_r($ret);
        $retList[] = [$domain,$msg?$msg:''];
    }
    TplUtil::echoSuccess($retList);

    //"webname_1=test1.com&ps=test1.com&path=%2Fwww%2Fwwwroot%2Ftest1.com&datauser=test1_com&datapassword=KBEjSNrshR&version=73&port=80&webname={\"domain\":\"test1.com\",\"domainlist\":[],\"count\":1}&ftp=false&sql=true&address=localhost&codeing=utf8&version=73";
}
//try{
    $ret = call_user_func_array($action,[json_encode($_REQUEST)]);
//}catch (Throwable $e){
//    print_r([$e]);
//}

echo json_encode($ret,JSON_UNESCAPED_UNICODE);
