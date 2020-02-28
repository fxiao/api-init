<?php

if (!function_exists('gmt_iso8601')) {
    //OSS
    function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }
}
if (!function_exists('time_format')) {
    function time_format($value)
    {

        //不超过1小时，显示多少分钟前
        //超过1小时，不超过今天的时间，显示 "今天 2:20"
        //超过今天，不超过昨天 显示 “昨天 2:20”
        //超过昨天 显示 “03-02 10:10”
        $inside_hour = time() - 3600 + 1;//1小时内的时间
        $value_str = strtotime($value);
//        dd($inside_hour);
        if ($value_str >= $inside_hour && $value_str <= time()) {
            return \Carbon\Carbon::parse($value)->diffForHumans();
        } elseif (\Carbon\Carbon::parse($value)->isToday()) {
            return "今天 " . date('H:i', $value_str);
        } elseif (\Carbon\Carbon::parse($value)->isYesterday()) {
            return "昨天 " . date('H:i', $value_str);
        } else {
            return date('n月j日 H:i', $value_str);
        }
    }
}

if (!function_exists('http_curl')) {
    function http_curl($url, $request_type = 'get', $arr = [])
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//定义一些curl参数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if ($request_type == 'post') {//如果是post请求

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        $output = curl_exec($ch);//执行请求
        curl_close($ch);
        return $output;
    }
}

if(! function_exists('create_sn')) {
    //生成订单编号
    function create_sn($type) {
        switch ($type) {
            case 'withdraw':
                $sn = 'wd' . str_random(30);
                break;
            case 'recharge':
                $sn = 'rc' . str_random(30);
                break;
            case "order":
                $sn = "od" . str_random(30);
                break;
            case "course_reward":
                $sn = "cr" . str_random(30);
                break;
            case "order_contact_king":
                $sn = "ck" . str_random(30);
                break;
        }

        return $sn;
    }
}

if (! function_exists('seed_one')) {
    function seed_one(string $table, array $data, string $pk = 'id') : int {
        if (\Schema::hasTable($table)) {
            if ($data) {
                $item = $data;
                $data = [$item];
                seeder_filter($item, $data, 0);
                if (\DB::table($table)->where($item)->count()) {
                    return intval(\DB::table($table)
                        ->select($pk)
                        ->where($item)
                        ->first()
                        ->$pk ?? 0
                    );
                } else {
                    echo 'Seeding table: ', $table, PHP_EOL;

                    return intval(\DB::table($table)->insertGetId($data[0]));
                }
            }
        }

        return 0;
    }
}

if (! function_exists('seed_once')) {
    function seed_once(string $table, array $data) {
        if (\Schema::hasTable($table)) {
            foreach ($data as $idx => $item) {
                if (is_array($item)) {
                    seeder_filter($item, $data, $idx);

                    if (\DB::table($table)->where($item)->count()) {
                        unset($data[$idx]);
                    }
                }
            }

            echo 'Seeding table: ', $table, PHP_EOL;

            if ($data) {
                \DB::table($table)->insert($data);
            }
        } else {
            echo 'Missing table: ', $table, ', skipped.', PHP_EOL;
        }
    }
}

if (! function_exists('seeder_filter')) {
    function seeder_filter(&$item, &$data = null, $idx = null) {
        array_walk($item, function (&$v, $k) use (
            &$data, &$item, $idx
        ) {
            if (is_array($v) && isset($v[0])) {
                if ($data && !empty_safe($idx)) {
                    $data[$idx][$k] = (string) $v[0];
                }

                unset($item[$k]);
            }
        });
    }
}

if (! function_exists('trans_times')) {
    //时间转换，秒换成时分秒
    function trans_times($seconds) {
        $days = floor($seconds/(3600*24));
        $seconds = $seconds%(3600*24);
        $hours = floor($seconds/3600);
        $seconds = $seconds%3600;
        $minutes = floor($seconds/60);
        $seconds = $seconds%60;

        return [
            "days"  =>  $days,
            "hours" =>  $hours,
            "minutes"   =>  $minutes,
            "seconds"   =>  $seconds
        ];
    }
}

if (! function_exists('check_email')) {
	/**
	 * 检测邮箱格式
	 * @param string $str 邮箱账号
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_email($str){
		if(empty($str)){ return false; }
		return preg_match("/^[A-Za-z0-9-_\.]+@([A-Za-z0-9-_]+\.)+[A-Za-z0-9]{2,6}$/",$str);
	}
}

if (! function_exists('check_tel')) {
	/**
	 * 检测手机号码
	 * @param string $str 手机号码
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_tel($str){
		if(empty($str)){ return false; }
		return preg_match("/^1[0-9]{10}$/",$str);
	}
}

if (! function_exists('check_phone')) {
	/**
	 * 检测电话号码
	 * @param string $str 电话号码
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_phone($str){
		if(empty($str)){ return false; }
		return preg_match('/^(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',$str);
	}
}

if (! function_exists('check_tel_or_phone')) {
	/**
	 * 检测电话号码和手机号码
	 * @param string $str 手机号码或电话号码
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_tel_or_phone($str){
		if(check_tel($str) || check_phone($str)){ return true; }
		return false;
	}
}

if (! function_exists('check_idcard')) {
	/**
	 * 检测身份证号(高级方法)
	 * @param string $idcard 身份证号码
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_idcard($idcard){
		if(empty($idcard)){ return false; }
		$City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
		$iSum = 0;
		$idCardLength = strlen($idcard);
		//长度验证
		if(!preg_match('/^\d{17}(\d|x)$/i',$idcard) and!preg_match('/^\d{15}$/i',$idcard)){ return false; }
		//地区验证
		if(!array_key_exists(intval(substr($idcard,0,2)),$City)){ return false; }
		//15位身份证验证生日，转换为18位
		if ($idCardLength == 15){
			$mon = intval(substr($idcard,8,2));
			if($mon>12 || $mon<1){ return false; }
			$day = intval(substr($idcard,10,2));
			if($day>31 || $day<1){ return false; }
			$sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
			//echo $sBirthday;die;
			$d = new \DateTime($sBirthday);
			$dd = $d->format('Y-m-d');
			if($sBirthday != $dd){ return false; }
			$idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
			$Bit18 = get_verify_bit($idcard);//算出第18位校验码
			$idcard = $idcard.$Bit18;
		}
		//判断是否大于2078年，小于1900年
		$year = substr($idcard,6,4);
		if ($year<1900 || $year>2078 ){ return false; }
		$mon = intval(substr($idcard,10,2));
		if($mon>12 || $mon<1){ return false; }
		$day = intval(substr($idcard,12,2));
		if($day>31 || $day<1){ return false; }
		//18位身份证处理
		$sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
		$d = new \DateTime($sBirthday);
		$dd = $d->format('Y-m-d');
		if($sBirthday != $dd){ return false; }
		//身份证编码规范验证
		$idcard_base = substr($idcard,0,17);
		if(strtoupper(substr($idcard,17,1)) != get_verify_bit($idcard_base)){ return false; }
		return true;
	}
}

if (! function_exists('check_url')) {
	/**
	 * 检测URL完整性
	 * @param string $str 被检测的URL地址
	 * @return 若检测通过则返回true，否则返回false
	 * @author IT攻城狮
	 */
	function check_url($str){
		if(empty($str)){ return false; }
		//return preg_match('/^https?:\/\/([[:alnum:]]|[:#%&_=\(\)\.\? \+\-@\/\$])+$/ui', $str);
		return preg_match('/^https?:\/\/([[:alnum:]]|[:#%&_,=\(\)\.\? \+\-@\/\$])+$/ui', $str);
	}
}


if (! function_exists('debug')) {
	/**
	 * 只保留最近7天的日志
	 */
	function debug($method,$str){
		$dir = './debug/';
		if(!is_dir($dir)){ mkdir($dir,0777); }
		mytxt($dir.date('Ymd').'.log', date('Y-m-d H:i:s').' | '.$method.' | '.$str."\r\n");
		$sum = 0;
		//删除超过7天的日志(文件倒叙排序)
		$list = scandir($dir,1);
		if(count($list)>9){
			foreach($list as $k=>$vo){
				if(is_dir($dir.$vo) || $vo=='.' || $vo=='..'){ continue; }
				if(is_file($dir.$vo)){
					$sum++;
					if($sum>7){
						unlink($dir.$vo);
					}
				}
			}
		}
	}
}


if (! function_exists('fb_get_merge_data')) {
	/**
	 * 合并数据
	 * @param array $data
	 * @param array $store
	 * @return 合并处理后的数据
	 */
	function fb_get_merge_data($data=array(),$store=array()){
		$d = array_merge($data, $store);
		if($store['unincorporated']==2){
			//不是非法人结算
			$d['legal_id_card_front_photo'] = '';
			$d['legal_id_card_back_photo'] = '';
			$d['unincorporated_photo'] = '';
		}
		
		$d['other_photo'] = $store['other_pic1'];
		$d['unity_category_id'] = $store['unity_category_id_1'];
		$d['unity_category_id_2'] = $store['unity_category_id_2'];
		
		unset($d['other_pic1']);
		unset($d['unity_category_id_1']);
		//unset($d['unity_category_id_2']);
		
		return $d;
	}
}


if (! function_exists('fb_check_condition')) {
	/**
	 * 检测是否符合要求
	 * @param array $data 要被检测的数据
	 * @return 检测通过，返回200状态码，否则返回错误代码
	 */
	function fb_check_condition($data){
		$message = '';
		if(empty($data['merchant_code'])){ $message .= '-100|商户号为空'."\r\n"; }
		if(empty($data['contact_phone'])){ $message .= '-101|商户手机号为空'."\r\n"; }
		if(empty($data['unity_category_id']) || empty($data['unity_category_id_2'])){ $message .= '-103|品类Id为空'."\r\n"; }
		if(empty($data['account_type'])){ $message .= '-104|账户类型为空'."\r\n"; }
		if(empty($data['real_name'])){ $message .= '-105|真实姓名为空'."\r\n"; }
		if(empty($data['id_card_no'])){ $message .= '-106|身份证号为空'."\r\n"; }
		if(empty($data['id_card_front_photo'])){ $message .= '-107|身份证人像面照片地址为空'."\r\n"; }
		if(empty($data['id_card_back_photo'])){ $message .= '-108|身份证国徽面照片地址为空'."\r\n"; }
		if(empty($data['bank_card_no'])){ $message .= '-109|银行卡号为空'."\r\n"; }
		
		if(empty($data['bank_card_image'])){ $message .= '-110|银行卡照片地址为空'."\r\n"; }
		if(empty($data['bank_cell_phone'])){ $message .= '-111|银行预留手机号为空'."\r\n"; }
		if(empty($data['bank_code'])){ $message .= '-112|所属银行编码为空'."\r\n"; }
		if(empty($data['store_name'])){ $message .= '-113|门店名称为空'."\r\n"; }
		if(empty($data['store_phone'])){ $message .= '-114|门店电话为空'."\r\n"; }
		if(empty($data['province_code'])){ $message .= '-115|省份编码为空'."\r\n"; }
		if(empty($data['city_code'])){ $message .= '-116|城市编码为空'."\r\n"; }
		if(empty($data['area_code'])){ $message .= '-117|区域编码为空'."\r\n"; }
		if(empty($data['street_address'])){ $message .= '-118|详细地址为空'."\r\n"; }
		if(empty($data['longitude'])){ $message .= '-119|门店地址经度为空'."\r\n"; }
		if($data['longitude']===1){ $message .= '-119|门店地址经度可能不正确'."\r\n"; }
		
		if(empty($data['latitude'])){ $message .= '-120|门店地址纬度为空'."\r\n"; }
		if($data['latitude']===1){ $message .= '-120|门店地址纬度可能不正确'."\r\n"; }
		if(empty($data['license_type'])){ $message .= '-121|门店经营许可类型为空'."\r\n"; }
		//如果门店经营许可类型为	营业执照(1)
		if($data['license_type']==1){
			if(empty($data['license_photo'])){ $message .= '-122|营业执照/证明函照片地址为空'."\r\n"; }
			if(empty($data['license_name'])){ $message .= '-124|营业执照名称为空'."\r\n"; }
			if(empty($data['license_id'])){ $message .= '-125|营业执照号为空'."\r\n"; }
			if(empty($data['license_time_type'])){ $message .= '-126|营业执照有效期类型为空'."\r\n"; }
			//门店经营许可类型为营业执照(1)		且营业执照有效期类型为正常有效期(1)时必填	日期格式：YYYY-MM-DD
			if(empty($data['license_time_type'])==1){
				if(empty($data['license_time_begin'])){ $message .= '-127|营业执照有效期开始时间为空'."\r\n"; }
				if(empty($data['license_time_end'])){ $message .= '-128|营业执照有效期终止时间为空'."\r\n"; }
			}
		}
		//如果门店经营许可类型为	证明函(2)
		if($data['license_type']==2){
			if(empty($data['hand_hold_id_card_pic'])){ $message .= '-123|手持身份证照片为空'."\r\n"; }
		}
		if(empty($data['store_front_img_url'])){ $message .= '-129|门头照片为空'."\r\n"; }
		
		if(empty($data['store_env_photo'])){ $message .= '-130|门店店内环境照片为空'."\r\n"; }
		if(empty($data['store_cash_photo'])){ $message .= '-131|门店收银台照片为空'."\r\n"; }
		
		//统一校验常见数据格式
		if(!empty($data['contact_phone']) && !check_tel($data['contact_phone'])){ $message .= '-132|商户手机号格式不正确'."\r\n"; }
		if(!empty($data['service_phone']) && !check_tel_or_phone($data['service_phone'])){ $message .= '-133|客服电话格式不正确'."\r\n"; }
		if(!empty($data['email']) && !check_email($data['email'])){ $message .= '-134|电子邮箱格式不正确'."\r\n"; }
		if(!empty($data['bank_cell_phone']) && !check_tel($data['bank_cell_phone'])){ $message .= '-135|银行预留手机号格式不正确'."\r\n"; }
		if(!empty($data['store_phone']) && !check_tel_or_phone($data['store_phone'])){ $message .= '-136|门店电话格式不正确'."\r\n"; }
		
		if(empty($message)){
			return message(200, '校验通过');
		}else{
			return message(-999, $message);
		}
	}
}



if (! function_exists('get_guid')) {
	/**
	 * 获取全球唯一数字
	 * @return 返回一个长度为32的字符串
	 * @author IT攻城狮
	 */
	function get_guid(){
		//array_count_values()可以学习一下这个函数
		//http://www.w3school.com.cn/php/func_array_count_values.asp
		//http://blog.csdn.net/ghostyusheng/article/details/53788087
		return strtolower(md5(uniqid(md5(microtime(true)),true)));
	}
}

if (! function_exists('get_http_image_lva')) {
	/**
	 * 获取图片完整路径(适用于laraver-admin)
	 * @param string $image 需要处理判断的图片路径
	 * @return 返回处理后的图片路径
	 * @author IT攻城狮
	 */
	function get_http_image_lva($image){
		if(empty($image)){ return ''; }
		if(check_url($image)){ return $image; }
		$ext = substr(strrchr($image, '.'), 1);
		if(!in_array($ext,array('jpg','png','jpeg','gif'))){ return $image; }
		return config('app.pc_url').'/upload/'.ltrim($image,"/");
	}
}

if (! function_exists('get_merchant_code')) {
	/**
	 * 获取商户号的方法(付呗商户号)
	 * @return 返回生成的商户号
	 * @author IT攻城狮
	 */
	function get_merchant_code($uuid=''){
		$uuid = $uuid==''?uniqid():$uuid;
		$key = "iicc"; //加盐
		$urlhash = md5($key . $uuid);
		$len = strlen($urlhash);
		
		//将加密后的串分成4段，每段4字节，对每段进行计算，一共可以生成四组短连接
		$urlhash_piece = substr($urlhash, 0, $len / 4);
		
		//将分段的位与0x3fffffff做位与，0x3fffffff表示二进制数的30个1，即30位以后的加密串都归零
		//此处需要用到hexdec()将16进制字符串转为10进制数值型，否则运算会不正常
		$hex = hexdec($urlhash_piece) & 0x3fffffff;
		
		//域名根据需求填写
		$short_url = "";
		
		//生成6位短网址
		for ($j = 0; $j < 6; $j++) {
			//将得到的值与0x0000003d,3d为61，即charset的坐标最大值
			$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			$short_url .= $charset[$hex & 0x0000003d];
			
			//循环完以后将hex右移5位
			$hex = $hex >> 5;
		}
			
		return 'FB'.$short_url;
	}
}

if (! function_exists('getLatLng')) {
	/**
	 * 根据详细地址调用百度API获取经纬度
	 * @param string $address 地址
	 * @param string $city  城市名
	 * @return 操作成功返回一个一维数组array('lat'=>'经度','lng'=>'纬度')
	 */
	function get_lat_lng($address='',$city='')
	{
		$result = array();
		$ak = 'WPF1bWdpbSGyPShtagm1owQWE87g25eg';//您的百度地图ak，可以去百度开发者中心去免费申请
		$url ="http://api.map.baidu.com/geocoding/v3/?address=".$address."&city=".$city."&output=json&ak=".$ak;
		$data = file_get_contents($url);
		//$data = '{"status":0,"result":{"location":{"lng":113.3310844896273,"lat":23.112222774059775},"precise":0,"confidence":50,"comprehension":100,"level":"旅游景点"}}';
		$data = json_decode($data,true);
		if (!empty($data) && $data['status'] == 0) {
			$result['lat'] = $data['result']['location']['lat'];
			$result['lng'] = $data['result']['location']['lng'];
			return $result;//返回经纬度结果
		}else{
			return null;
		}
	}
}

if (! function_exists('message')) {
	/**
	 * 自定义返回消息格式
	 * @param string $code
	 * @param string $info
	 * @return 返回消息对象
	 */
	function message($code,$info){
		
		return array('code'=>intval($code),'info'=>$info);
	}
}

if (! function_exists('mytxt')) {
	/**
	 * 写文件
	 */
	function mytxt($filename,$str){
		$fp = fopen($filename,'a');
		if($fp){
			$startTime = microtime();
			do{
				$canWrite = flock($fp, LOCK_EX);
				if(!$canWrite) usleep(round(rand(0, 100)*1000));
			}while ((!$canWrite)&& ((microtime()-$startTime) < 1000));
			if($canWrite){ fwrite($fp, $str); }
			fclose($fp);
		}
	}
}

if (! function_exists('get_verify_bit')) {
	/**
	 * 计算身份证校验码，根据国家标准GB 11643-1999
	 * @return 校验成功返回身份证号末尾字符，否则返回false
	 * @author IT攻城狮
	 */
	function get_verify_bit($idcard_base){
		if(strlen($idcard_base) != 17){ return false; }
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++){
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
}

if (! function_exists('pc_path')) {
	/**
	 * 获取PC文件夹完整路径(包含PC和结尾斜杠)
	 * @return 返回PC项目的根目录(包含pc文件夹和结尾斜杠)
	 * @author IT攻城狮
	 */
	function pc_path(){
		return config('app.pc_path');
	}
}

if (! function_exists('fn_open_id')) {
    function fn_open_id()
    {
        $uuid = uniqid();
        $key = "fnbbq"; //加盐
        $urlhash = md5($key . $uuid);
        $len = strlen($urlhash);

        //将加密后的串分成4段，每段4字节，对每段进行计算，一共可以生成四组短连接
        for ($i = 0; $i < 4; $i++) {
        $urlhash_piece = substr($urlhash, $i * $len / 4, $len / 4);

        //将分段的位与0x3fffffff做位与，0x3fffffff表示二进制数的30个1，即30位以后的加密串都归零
        //此处需要用到hexdec()将16进制字符串转为10进制数值型，否则运算会不正常
        $hex = hexdec($urlhash_piece) & 0x3fffffff;

        //域名根据需求填写
        $short_url = "";

        //生成6位短网址
        for ($j = 0; $j < 6; $j++) {
            //将得到的值与0x0000003d,3d为61，即charset的坐标最大值
            $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $short_url .= $charset[$hex & 0x0000003d];

            //循环完以后将hex右移5位
            $hex = $hex >> 5;
        }

            $short_url_list[] = $short_url;
        }

        return $short_url_list;
    }
}
