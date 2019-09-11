<?php
 
    // 内部类不会有重复包含类名冲突问题
    class FastCGIClientImpl
    {
        const FCGI_HEADER_LEN = 0x08;
        const FCGI_VERSION_1 = 0x01;
 
        // 可用于FCGI_Header的type组件的值
        const FCGI_BEGIN_REQUEST = 1;
        const FCGI_ABORT_REQUEST =  2;
        const FCGI_END_REQUEST  = 3;
        const FCGI_PARAMS  = 4;
        const FCGI_STDIN  = 5;
        const FCGI_STDOUT  = 6;
        const FCGI_STDERR  = 7;
        const FCGI_DATA  = 8;
        const FCGI_GET_VALUES  = 9;
        const FCGI_GET_VALUES_RESULT  = 10;
        const FCGI_UNKNOWN_TYPE  = 11;
        const FCGI_MAXTYPE = self::FCGI_UNKNOWN_TYPE;
 
 
        //
        const FCGI_NULL_REQUEST_ID  = 0x00;
 
        // 可用于FCGI_BeginRequestBody的flags组件的掩码
        const FCGI_KEEP_CONN = 1;
        // 可用于FCGI_BeginRequestBody的role组件的值
        const FCGI_RESPONDER = 1;
        const FCGI_AUTHORIZER = 2;
        const FCGI_FILTER = 3;
 
        // Values for protocolStatus component of FCGI_EndRequestBody
        const FCGI_REQUEST_COMPLETE = 0;
        const FCGI_CANT_MPX_CONN    = 1;
        const FCGI_OVERLOADED       = 2;
        const FCGI_UNKNOWN_ROLE     = 3;
 
 
        // members
        public $_sock = null;
        public $_app_status = 0;
        public $_fcgi_status_code = -1;
        public $_stderr_content = '';
        public $_stdout_raw_content = '';
        public $_stdout_real_content = '';
        public $_response = '';
        public $_http_status_code = 200;
        public $_http_status_msg = 'OK';
        public $_http_resp_headers = array();
 
 
        // 构造函数
        function __construct()
        {
        }
 
        // 是否是可添加padding的协议记录类型
        function isPadableRecord($appRecordType)
        {
            if ($appRecordType == self::FCGI_BEGIN_REQUEST
                || $appRecordType == self::FCGI_ABORT_REQUEST
                || $appRecordType == self::FCGI_END_REQUEST
                || $appRecordType == self::FCGI_STDIN
                ) {
                return true;
            }
 
            return false;
        }
 
        // 构造fastcgi头
        function fastcgiHeader($appRecordType, $contentLength)
        {
            assert($appRecordType >= self::FCGI_BEGIN_REQUEST);
            assert($contentLength >= 0);
 
            $hdr = '';
            $hdr .= pack('C', self::FCGI_VERSION_1);  // version
            $hdr .= pack('C', $appRecordType);            // type: self::FCGI_BEGIN_REQUEST
 
            $hdr .= pack('n', rand(1, 65535));        // rid1,rid0
 
            $hdr .= pack('n', $contentLength);     // clen1,clen0
 
            if ($this->isPadableRecord($appRecordType)) {
                $hdr .= pack('C', rand(0, 255));            // padlen
            } else {
                $hdr .= pack('C', 0x00);            // padlen
            }
            $hdr .= pack('C', 0x00);            // reserved
 
            assert(strlen($hdr) == self::FCGI_HEADER_LEN);
 
            return $hdr;
        }
 
        // Build a FastCGI packet
        function buildPacket($appRecordType, $content)
        {
            $hdr = $this->fastcgiHeader($appRecordType, strlen($content));
            $pkt = $hdr . $content;
 
            $padtext = '';
            $padlen = unpack('C', $hdr{6})[1];
            if ($this->isPadableRecord($appRecordType)) {
                if ($padlen > 0) {
                    $padtext = str_repeat(chr(rand(0, 255)), $padlen);
                    $pkt .= $padtext;
                    assert(strlen($padtext) == $padlen);
                }
 
                // 为什么FastCGIConst:FCGI_PARAMS加padding后协议有问题
            }
 
            assert(strlen($pkt) == ($padlen + strlen($content) + self::FCGI_HEADER_LEN));
            return $pkt;
        }
 
        // Build an FastCGI Name value pair
        function buildNvpair($name, $value)
        {
            $nlen = strlen($name);
            $vlen = strlen($value);
 
            $nvpair = '';
            $nvpair .= $nlen < 128 ? pack('C', $nlen) : pack('N', $nlen | (0x01 << 31));
            $nvpair .= $vlen < 128 ? pack('C', $vlen) : pack('N', $vlen | (0x01 << 31));
 
            assert(strlen($nvpair) == 2 || strlen($nvpair) == 5 || strlen($nvpair) == 8);
 
            $nvpair .= $name . $value;
            return $nvpair;
        }
 
        // 构造协议开始请求记录包
        function buildBeginRequest()
        {
            $beginRequestBody = pack('n', self::FCGI_RESPONDER) // role1,role0
            . pack('CNC', 0x00, rand(), 0x00)                              // flag, reserved5
            ;
            assert(strlen($beginRequestBody) == 8);
 
            $pkt = $this->buildPacket(self::FCGI_BEGIN_REQUEST, $beginRequestBody);
            $padlen = unpack('C', $pkt{6})[1];
            assert(strlen($pkt) == (self::FCGI_HEADER_LEN * 2 + $padlen));
 
            return $pkt;
        }
 
        // 构造协议中断请求记录包
        function buildAbortRequest()
        {
            $pkt = $this->buildPacket(self::FCGI_ABORT_REQUEST, '');
            return $pkt;
        }
 
        // 构造协议参数请求记录包
        function buildParamsRequest(array $params)
        {
            $pstr = '';
            foreach ($params as $name => $value) {
                $pstr .= $this->buildNvpair($name, $value);
            }
 
            $pkt = '';
            $pkt .= $this->buildPacket(self::FCGI_PARAMS, $pstr);
            $pkt .= $this->buildPacket(self::FCGI_PARAMS, '');   // 为什么需要构建一个空包
 
            return $pkt;
        }
 
        // 构造协议标准输入请求记录包
        function buildStdinRequest($stdin)
        {
            $pkt = $this->buildPacket(self::FCGI_STDIN, $stdin);
            $pkt .= $this->buildPacket(self::FCGI_STDIN, '');   // 为什么需要构建一个空包
            return $pkt;
        }
 
        // 构造协议结束请求记录包
        function buildEndRequest()
        {
            $body = pack('N', 301)
            . pack('C', 0x01)
            . pack('C*', 0x00, 0x00, 0x00)
            ;
            assert(strlen($body) == self::FCGI_HEADER_LEN);
 
            $pkt = $this->buildPacket(self::FCGI_END_REQUEST, $body);
 
            return $pkt;
        }
 
        /**
         * Decode a FastCGI Packet
         *
         * @param String $data String containing all the packet
         * @return array
         */
        function decodePacketHeader($data)
        {
            $ret = array();
            $ret['version']       = unpack('C', $data{0})[1];
            $ret['type']          = unpack('C', $data{1})[1];
            $ret['requestId']     = unpack('n', substr($data, 2, 2))[1];
            $ret['contentLength'] = unpack('n', substr($data, 4, 2))[1];
            $ret['paddingLength'] = unpack('C', $data{6})[1];
            $ret['reserved']      = unpack('C', $data{7})[1];
            return $ret;
        }
 
        /**
         * Read a FastCGI Packet
         *
         * @return array
         */
        function readPacket()
        {
            if ($packet = fread($this->_sock, self::FCGI_HEADER_LEN)) {
                $resp = $this->decodePacketHeader($packet);
                $resp['content'] = '';
                if ($resp['contentLength']) {
                    $len = $resp['contentLength'];
                    while ($len && $buf = fread($this->_sock, $len)) {
                        $len -= strlen($buf);
                        $resp['content'] .= $buf;
                        $buf = '';
                    }
                }
                if ($resp['paddingLength']) {
                    $buf = fread($this->_sock, $resp['paddingLength']);
                }
                return $resp;
            } else {
                return false;
            }
        }
 
        // 连接到FastCGI服务器
        function connect($host, $port)
        {
            $fp = null;
            if ($this->_sock == null) {
                $fp = fsockopen($host, $port);
                if (!$fp) {
                    return false;
                }
            }
            $this->_sock = $fp;
            assert($this->_sock != null);
            return true;
        }
 
        // 断开到FastCGI服务器的连接
        function disconnect()
        {
            if (is_resource($this->_sock)) {
                fclose($this->_sock);
                $this->_sock = null;
            }
        }
 
        // 解析HTTP响应头信息
        function parseHttpHeader($stdout)
        {
            $hdr_end_pos = strpos($stdout, "\r\n\r\n");
            if ($hdr_end_pos < 0) {
                return false;
            }
 
            $raw_hdr = explode("\r\n", substr($stdout, 0, $hdr_end_pos));
            foreach ($raw_hdr as $lineno => $row) {
                $kvpair = explode(': ', $row);
                if ($lineno == 0 && $kvpair[0] == 'Status') {
                    $this->_http_status_code = explode(' ', trim($kvpair[1]))[0];
                    $this->_http_status_msg = substr($kvpair[1], strlen($this->_http_status_code)+1);
                    $this->_http_status_msg = trim($this->_http_status_msg);
                } else {
                    $this->_http_resp_headers[$kvpair[0]] = $kvpair[1];
                }
            }
 
            $this->_stdout_real_content = substr($stdout, $hdr_end_pos + 4);
 
            return true;
        }
 
        // 比较全面的测试方法
        public function requestFullTest(array $params, $stdin)
        {
            $fp = fsockopen('127.0.0.1', 9000);
            var_dump($fp);
            $this->_sock = $fp;
 
            $breq = $this->buildBeginRequest();
            $data = $breq;
 
            $str = $this->buildParamsRequest($params);
            $data .= $str;
 
            $str = $this->buildStdinRequest($stdin);
            $data .= $str;
 
            $str = $this->buildAbortRequest();
            $data .= $str;
 
            $str = $this->buildEndRequest();
            $data .= $str;
 
            $ret = fwrite($fp, $data, strlen($data));
            // var_dump($ret);
 
            // $res = fread($fp, 3000);
            // $res = $readPacket();
            $response = '';
            $stdout_content = '';
            $stderr_content = '';
            $cnter = 0;
            do {
                $btime = microtime(true);
                $resp = $this->readPacket();
                $now = microtime(true);
                $dtime = $now - $btime;
                echo "read a pkt: {$cnter} on {$now}, used: {$dtime}\n"; $cnter++;
                if ($resp['type'] == self::FCGI_STDOUT || $resp['type'] == self::FCGI_STDERR) {
                    $response .= $resp['content'];
                }
                if ($resp['type'] == self::FCGI_STDOUT) {
                    $stdout_content .= $resp['content'];
                }
                if ($resp['type'] == self::FCGI_STDERR) {
                    $stderr_content .= $resp['content'];
                }
            } while ($resp && $resp['type'] != self::FCGI_END_REQUEST);
 
            print_r($resp);
            var_dump("response={$response}", "stdout={$stdout_content}", "stderr={$stderr_content}");
            sleep(5);
 
            fclose($fp);
            return true;
        }
 
 
        // 对外执行fastcgi调用的方法
        function request(array $params, $stdin)
        {
            $breq = $this->buildBeginRequest();
            $data = $breq;
 
            $str = $this->buildParamsRequest($params);
            $data .= $str;
 
            $str = $this->buildStdinRequest($stdin);
            $data .= $str;
 
            $ret = fwrite($this->_sock, $data, strlen($data));
            if (!$ret) {
                assert($ret == strlen($data));
                return false;
            }
 
            $resp = null;
            $cnter = 0;
            do {
                $btime = microtime(true);
                $resp = $this->readPacket();
                $now = microtime(true);
                $dtime = $now - $btime;
                echo "read a pkt: {$cnter} on {$now}, used: {$dtime}\n"; $cnter++;
                if ($resp['type'] == self::FCGI_STDOUT || $resp['type'] == self::FCGI_STDERR) {
                    $this->_response .= $resp['content'];
                }
                if ($resp['type'] == self::FCGI_STDOUT) {
                    $this->_stdout_raw_content .= $resp['content'];
                }
                if ($resp['type'] == self::FCGI_STDERR) {
                    $this->_stderr_content .= $resp['content'];
                }
            } while ($resp && $resp['type'] != self::FCGI_END_REQUEST);
 
            if (!$resp) {
                return false;
            }
 
            assert(strlen($resp['content']) == self::FCGI_HEADER_LEN);
            $this->_app_status = unpack('N', substr($resp['content'], 0, 4))[1];
            $this->_fcgi_status = unpack('C', $resp['content']{4})[1];
 
            return true;
        }
 
    }; // end class FastCGIClientImpl
?>

<?php
//demo
$client = new FastCGIClientImpl();
$content = 'key123=value456&keyabc=valueefggg&中文=abcdefg&hehe=汉字utf8的';
$res = $client->__invoke(
                      array(
                            'GATEWAY_INTERFACE' => 'FastCGI/1.0',
                            'REQUEST_METHOD' => 'POST',
                            'DOCUMENT_ROOT' => '/data1/vhosts/photo.house.kitech.com.cn',
                            'SCRIPT_FILENAME' => '/data1/vhosts/photo.house.kitech.com.cn/index.php',
                            'SCRIPT_NAME' => '/index.php',
                            'REQUEST_URI' => '/test/test6/simpost',
                            'SERVER_SOFTWARE' => 'php/fastcgiclient',
                            'REMOTE_ADDR' => '127.0.0.1',
                            'REMOTE_PORT' => '9985',
                            'SERVER_ADDR' => '127.0.0.1',
                            'SERVER_PORT' => '80',
                            'SERVER_NAME' => 'photo.house.kitech.com.cn',
                            'HTTP_HOST' => 'photo.house.kitech.com.cn',
                            'SERVER_PROTOCOL' => 'HTTP/1.0',
                            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
                            'CONTENT_LENGTH' => strlen($content),
                            'kitech.com.cn_CACHE_DIR' => '',
                            'kitech.com.cn_DATA_DIR' => '',
                            'kitech.com.cn_RSYNC_SERVER' => '',
                            'kitech.com.cn_STORAGE_SERVER' => '',
                            'kitech.com.cn_RSYNC_MODULES' => '',
                            'kitech.com.cn_RESOURCE_URL' => '',
                            'kitech.com.cn_DIST_URL' => '',
                            'kitech.com.cn_TAAA_127' => 'DallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDall123456789012345',
                            'kitech.com.cn_TAAA_128' => 'DallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDallasDall1234567890123456',
                                   ),
                      $content
                      );
 
var_dump($res);
?>
