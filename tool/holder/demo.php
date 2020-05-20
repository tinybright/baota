<?php
function actionTest1($step = 2){
    set_time_limit(0);

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
    //$data = 'test2.com';
    $domains = ['wp1.com','wp2.com'];
    //$domains = [$data];
    $domains0 = @$domains[0];
    $siteName = $domains0;
    //$host = CheckUtil::getValue($params,'host','local');
    //$data = 'wp.zggb999.org,wp.zhainanyouhuo.com,wp.zhaoweisuliao.org,wp.zmcqww.org,wp.zzydx.com';
    //
    //
    //644623b8842b72ca39e29cb4ae69f804__3Rp3UniqZeqP8Y2U6qeORanDHBYr1aDz
    $btUrl = 'http://104.168.134.97:8888';
    $btKey = '1234';
    $btKey = '3Rp3UniqZeqP8Y2U6qeORanDHBYr1aDz';
    $btKey = 'Pjy3ehPjuvwyYNBv1VZb3ycypa7yDFyl';
    $btApi = new BtApi($btUrl,$btKey);
    $actions = 'SetupPackage';
    //$step = 2;
    //http://test3.com/wp-admin/install.php?step=1&language=zh_CN
    if($step == 'addSite'){
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
        print_r($p_data);
        $result = $btApi->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);

        TplUtil::prepareOutput();
        print_r($result);

        print_r($data);
        return;
    }
    if($step == 'setupWp'){
        //$weblog_title         = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
        //$user_name            = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
        //$admin_password       = isset( $_POST['admin_password'] ) ? wp_unslash( $_POST['admin_password'] ) : '';
        //$admin_password_check = isset( $_POST['admin_password2'] ) ? wp_unslash( $_POST['admin_password2'] ) : '';
        //$admin_email          = isset( $_POST['admin_email'] ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';
        //$public               = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : 1;

        $ret = NetUtil::post('http://wp1.com/wp-admin/install.php?step=2',[
            //'step'=>'2',
            'weblog_title'=>'weblog_title1',
            'user_name'=>'user_name1',
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
    if($step == 'installWp'){

        //dname: wordpress
        //site_name: wp3.com
        //php_version: 73
        $btApi = new BtApi($btUrl,$btKey);
        $url = $btApi->BT_PANEL.'/deployment?action=SetupPackage';
        $p_data = $btApi->GetKeyData();		//取签名
        $p_data['dname'] = 'wordpress';
        //找网站
        $p_data['site_name'] = 'wp1.com';
        $p_data['php_version'] = '73';
        print_r($p_data);
        $result = $btApi->HttpPostCookie($url,$p_data);
        //解析JSON数据
        $data = json_decode($result,true);
        TplUtil::prepareOutput();
        print_r($result);
        //{"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
        print_r($data);
        /**
         * {"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
         */
        return;
        //'http://localhost:8888/deployment?action=SetupPackage';
        //dname=wordpress&site_name=test3.com&php_version=73
        //{"status": true, "msg": {"db_config": "", "run_path": "/", "php_versions": "53,54,55,56,70,71,72,73,74", "admin_username": "", "success_url": "/index.php", "chmod": [{"path": "/wp-admin", "mode": 754}, {"path": "/wp-includes", "mode": 700}], "remove_file": ["/install", "/temp", "/.user.ini"], "php_ext": ["pathinfo", "opcache"], "admin_password": ""}}
    }

    if($step == 'setupWpConfig'){
        $url = 'http://127.0.0.1/holder.php?action=get_wp_config&dbName=wp1_com&dbUsername=wp1_com&dbPassword=datapassword1&domain=wp1.com';
        $result = $btApi->HttpPostCookie($url,[]);
        print_r([$result]);
        return;
    }


    $skipLink = $btApi->BT_PANEL.'?license=true';
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
    return;

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
