<?php
/**
 * 宝塔API接口示例Demo
 * 仅供参考，请根据实际项目需求开发，并做好安全处理
 * date 2018/12/12
 * author 阿良
 */
class BtApi {
	public $BT_KEY = "";  //接口密钥
  	public $BT_PANEL = "";	   //面板地址
	
  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
  	//示例取面板日志	
	public function GetLogs(){
		//拼接URL地址
		$url = $this->BT_PANEL.'/data?action=getData';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['table'] = 'logs';
		$p_data['limit'] = 10;
		$p_data['tojs'] = 'test';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}

    public function getDomainList(){
        //拼接URL地址
        //$url = $this->BT_PANEL.'/data?action=getData';
        $url = $this->BT_PANEL.'/data?action=getData';

        //准备POST数据
        $p_data = $this->GetKeyData();		//取签名
        $p_data['table'] = 'domain';
        $p_data['list'] = true;
        $p_data['search'] = 1;

        //BtApi Object
        //(
        //    [BT_KEY] => aKxkGUe9aiTYek7qNCz6v64emeYpmJzT
        //    [BT_PANEL] => http://192.69.91.55:8888
        //)
        //Array
        //(
        //    [url] => http://192.69.91.55:8888/site?action=AddSite
        //)
        //Array
        //(
        //    [request_token] => 57a2504da316459095e1f5b09e86ce31
        //    [request_time] => 1583672970
        //    [path] => /www/wwwroot/wp
        //print_r(['url'=>$url]);
        //print_r($p_data);

        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);
        return $data;
    }

    public function getSiteList(){
        //拼接URL地址
        //$url = $this->BT_PANEL.'/data?action=getData';
        $url = $this->BT_PANEL.'/data?action=getData';

        //准备POST数据
        $p_data = $this->GetKeyData();		//取签名
        $p_data['table'] = 'sites';
        $p_data['limit'] = 5;
        $p_data['order'] = 'id asc';

        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);
        return $data;
    }

    public function getSite(){
        //拼接URL地址
        //$url = $this->BT_PANEL.'/data?action=getData';
        $url = $this->BT_PANEL.'/data?action=getData';

        //准备POST数据
        $p_data = $this->GetKeyData();		//取签名
        $p_data['table'] = 'sites';
        $p_data['limit'] = 1;
        $p_data['order'] = 'id asc';

        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);
        return $data;
    }

    public function addDomain($id,$siteName,$domain){
        //拼接URL地址
        //$url = $this->BT_PANEL.'/data?action=getData';
        $url = $this->BT_PANEL.'/site?action=AddDomain';

        //准备POST数据
        $p_data = $this->GetKeyData();		//取签名
        $p_data['id'] = $id;
        $p_data['webname'] = $siteName;
        $p_data['domain'] = $domain;

        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);

        //解析JSON数据
        $data = json_decode($result,true);
        return $data;
    }
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	public function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time,
            'request_ignore'=>1
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    public function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        //$cookie_file='./'.md5($this->BT_PANEL).'.cookie';
        //if(!file_exists($cookie_file)){
        //    $fp = fopen($cookie_file,'w+');
        //    fclose($fp);
        //}
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       	//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
$params = @$_REQUEST;
$api = @$params['api'];
switch($api){
    case 'getDomainList':
        $btApi = new BtApi('http://192.69.91.55:8888',UibotUtil::$key);
        $ret = $btApi->getDomainList();
        print_r($ret);
        break;
    case 'addDomain':
        $domain = @$params['domain'];
        $id = @$params['id']?$params['id']:1;
        $siteName = @$params['siteName']?$params['siteName']:'bwfaka.top';
        $btApi = new BtApi('http://192.69.91.55:8888',UibotUtil::$key);
        $ret = $btApi->addDomain($id,$siteName,$domain);
        //Array ( [status] => 1 [msg] => 域名添加成功! )
        //Array ( [status] =>  [msg] => 域名添加成功! )
        print_r($ret);
        break;
    case 'getSiteList':
        $btApi = new BtApi('http://192.69.91.55:8888',UibotUtil::$key);
        #Array ( [data] => Array ( [0] => Array ( [status] => 1 [ps] => false.com [domain] => 4 [name] => false.com [addtime] => 2020-03-07 18:48:34 [path] => /www/wwwroot/false.com [backup_count] => 0 [edate] => 0000-00-00 [id] => 2 ) [1] => Array ( [status] => 1 [ps] => bwfaka.top [domain] => 3 [name] => bwfaka.top [addtime] => 2020-03-07 11:12:16 [path] => /www/wwwroot/wp [backup_count] => 0 [edate] => 0000-00-00 [id] => 1 ) ) [where] => [page] =>
        //1共2条数据
//)
        $ret = $btApi->getSiteList();
        //id name path domain[num]
        print_r($ret);
        break;
    case 'getSite':
        $btApi = new BtApi('http://192.69.91.55:8888',UibotUtil::$key);
        #Array ( [data] => Array ( [0] => Array ( [status] => 1 [ps] => false.com [domain] => 4 [name] => false.com [addtime] => 2020-03-07 18:48:34 [path] => /www/wwwroot/false.com [backup_count] => 0 [edate] => 0000-00-00 [id] => 2 ) [1] => Array ( [status] => 1 [ps] => bwfaka.top [domain] => 3 [name] => bwfaka.top [addtime] => 2020-03-07 11:12:16 [path] => /www/wwwroot/wp [backup_count] => 0 [edate] => 0000-00-00 [id] => 1 ) ) [where] => [page] =>
        //1共2条数据
        //)
        $ret = $btApi->getSite();
        print_r($ret);
        break;

}



//实例化对象
//$api = new bt_api();
////获取面板日志
//$r_data = $api->GetLogs();
////输出JSON数据到浏览器
//echo json_encode($r_data);

?>
