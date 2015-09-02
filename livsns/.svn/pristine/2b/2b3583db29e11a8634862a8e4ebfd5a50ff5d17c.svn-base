<?php

/**
 * OAuth2.0 draft v10 exception handling.
 *
 * @author Originally written by Naitik Shah <naitik@facebook.com>.
 * @author Update to draft v10 by Edison Wong <hswong3i@pantarei-design.com>.
 *
 * @sa <a href="https://github.com/facebook/php-sdk">Facebook PHP SDK</a>.
 */
class OAuth2Exception extends Exception {/*{{{*/
	
	/**
	 * The result from the API server that represents the exception information.
	 */
	protected $result;

	/**
	 * Make a new API Exception with the given result.
	 *
	 * @param $result
	 * The result from the API server.
	 */
	public function __construct($result) {
		$this->result = $result;
		
		$code = isset($result['code']) ? $result['code'] : 0;
		
		if (isset($result['error'])) {
			// OAuth 2.0 Draft 10 style
			$message = $result['error'];
		} elseif (isset($result['message'])) {
			// cURL style
			$message = $result['message'];
		} else {
			$message = 'Unknown Error. Check getResult()';
		}
		
		parent::__construct($message, $code);
	}

	/**
	 * Return the assochated result object returned by the API server.
	 *
	 * @returns
	 * The result from the API server.
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Returns the assochated type for the error. This will default to
	 * 'Exception' when a type is not available.
	 *
	 * @return
	 * The type for the error.
	 */
	public function getType() {
		if (isset($this->result['error'])) {
			$message = $this->result['error'];
			if (is_string($message)) {
				// OAuth 2.0 Draft 10 style
				return $message;
			}
		}
		return 'Exception';
	}

	/**
	 * To make debugging easier.
	 *
	 * @returns
	 * The string representation of the error.
	 */
	public function __toString() {
		$str = $this->getType() . ': ';
		if ($this->code != 0) {
			$str .= $this->code . ': ';
		}
		return $str . $this->message;
	}
}/*}}}*/


class OAuth2Client {/*{{{*/

    private $_client_id;
    private $_client_secret;
    private $_access_token;

    private $_base_uri;
    private $_authorize_uri;
    private $_access_token_uri;

    
	/**
	 * Initialize OAuth2.0 Client.
	 *
	 * @param $client_id
     * The application ID.
     * @param $client_secret
     * The application secret.
     *
     * @return void
	 */
	public function __construct($client_id, $client_secret, $end_point, $authorize_uri, $access_token_uri) {
        $this->_client_id = $client_id;
        $this->_client_secret = $client_secret;
        $this->_base_uri = $end_point;
        $this->_authorize_uri = $authorize_uri;
        $this->_access_token_uri = $access_token_uri;
	}

    /*
     * Set Access Token
     *
     * @param $access_token
     * The application access token
     * @return void
     */
    public function setAccessToken($access_token) {
        $this->_access_token = $access_token;
    }

	/**
	 * Make an API call.
	 *
	 *
	 * @param $path
	 * The target path, relative to base_path/service_uri or an absolute URI.
	 * @param $method
	 * (optional) The HTTP method (default 'GET').
	 * @param $params
	 * (optional The GET/POST parameters.
	 *
	 * @return
	 * The JSON decoded response object.
	 *
	 * @throws OAuth2Exception
	 */
	public function request($path, $method = 'GET', $params = array(), $fh = NULL) {
        if (is_null($params)) $params = array();
		if (is_array($method) && empty($params)) {
			$params = $method;
			$method = 'GET';
		}
		
		// json_encode all params values that are not strings.
		foreach ( $params as $key => $value ) {
			if (!is_string($value)) {
				$params[$key] = json_encode($value);
			}
		}
		
		$result = json_decode($this->makeOAuth2Request($this->getUri($path), $method, $params, $fh),1);
		/*if (is_array($result) && isset($result['error'])) {
			throw new OAuth2Exception($result);
		}*/
		return $result;
	}
	

	/**
	 * Default options for cURL.
	 */
	public static $CURL_OPTS = array(
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTPHEADER => array("Accept: application/json")
	);

   function getAuthorizeURL($response_type = 'code', $state = NULL, $display = NULL) {/*{{{*/
       $params = array(
           'client_id'  => $this->_client_id,
           'redirect_uri' => $url,
           'response_type' => 'code',
           'state'  => $state,
           'display'    => $display,
       );
        return $this->_authorize_uri. "?" . http_build_query($params);
    } /*}}}*/
    

	/**
	 * Gets an OAuth2.0 access token
	 *
	 * @return
	 * The valid OAuth2.0 access token.
	 */
	public function getAccessToken($code, $redirect_uri) {
        return json_decode($this->makeRequest(
            $this->_access_token_uri,
            'POST',
            array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->_client_id,
                'client_secret' => $this->_client_secret,
                'code' => $code,
                'redirect_uri' => $redirect_uri
            )
        ));
	}

    /**
     * Gets an OAuth2.0 Access Token by password
     *
	 * @return
	 * The valid OAuth2.0 access token.
	 */
	public function getAccessTokenByPassword($username, $password) {
        return json_decode($this->makeRequest(
            $this->_access_token_uri,
            'POST',
            array(
                'grant_type'    => 'password',
                'client_id'     => $this->_client_id,
                'client_secret' => $this->_client_secret,
                'username'      => $username,
                'password'      => $password,
                //'redirect_uri'  => $redirect_uri
            )
        ));
	}


	public function customRequest($path, $method = 'GET', $params = array()) {
        $default = array(
            'client_id'     => $this->_client_id,
            'client_secret' => $this->_client_secret,
        );
        return json_decode($this->makeRequest(
            $this->_base_uri . $path,
            $method,
            array_merge($default, $params)
        ));
    }

	/**
	 * Make an OAuth2.0 Request.
	 *
	 * @param $path
	 * The target path, relative to base_path/service_uri or an absolute URI.
	 * @param $method
	 * (optional) The HTTP method (default 'GET').
	 * @param $params
	 * (optional The GET/POST parameters.
	 *
	 * @return
	 * The JSON decoded response object.
	 *
	 * @throws OAuth2Exception
	 */
	protected function makeOAuth2Request($path, $method = 'GET', $params = array(), $fh) {
        if ($this->_access_token) $params['access_token'] = $this->_access_token;

		return $this->makeRequest($path, $method, $params, $fh);
	}

	/**
	 * Makes an HTTP request.
	 *
	 * @param $path
	 * The target path, relative to base_path/service_uri or an absolute URI.
	 * @param $method
	 * (optional) The HTTP method (default 'GET').
	 * @param $params
	 * (optional The GET/POST parameters.
	 * @param $fh
	 * (optional) An initialized file handle
	 *
	 * @return
	 * The JSON decoded response object.
	 */
	protected function makeRequest($path, $method = 'GET', $params = array(), $fh = NULL) {
        $ch = curl_init();
		
		$opts = self::$CURL_OPTS;
        if (!is_null($fh)) {
            unset($opts[CURLOPT_HTTPHEADER][0]);
            $opts[CURLOPT_FILE] = $fh;
        }

        // upyun oauth2 only accept access token in headers
        if (isset($params['access_token'])) {
            $opts[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $params['access_token'];
            unset($params['access_token']);
        }

		$opts[CURLOPT_CUSTOMREQUEST] = $method;
		if ($params) {
			switch ($method) {
				case 'GET':
                case 'DELETE':
                    if (count($params) > 0) $path .= '?' . http_build_query($params, NULL, '&');
					break;
				default :
                    //$opts[CURLOPT_POSTFIELDS] = http_build_query($params, NULL, '&');
                    $opts[CURLOPT_POSTFIELDS] = $params;
			}
		}
        else {
            $opts[CURLOPT_HTTPHEADER][] = 'Content-Length: 0';
        }
		$opts[CURLOPT_URL] = $path;
		
		// Disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
		// for 2 seconds if the server does not support this header.
		if (isset($opts[CURLOPT_HTTPHEADER])) {
			$existing_headers = $opts[CURLOPT_HTTPHEADER];
			$existing_headers[] = 'Expect:';
			$opts[CURLOPT_HTTPHEADER] = $existing_headers;
		} else {
			$opts[CURLOPT_HTTPHEADER] = array('Expect:');
		}
		
       //var_dump($params);
		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		
		if ($result === FALSE) {
			$e = new OAuth2Exception(array('code' => curl_errno($ch), 'message' => curl_error($ch)));
			curl_close($ch);
			throw $e;
		}
		curl_close($ch);
		
		return $result;
	}



	/**
	 * Since $_SERVER['REQUEST_URI'] is only available on Apache, we
	 * generate an equivalent using other environment variables.
	 */
	function getRequestUri() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			if (isset($_SERVER['argv'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
			} elseif (isset($_SERVER['QUERY_STRING'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = $_SERVER['SCRIPT_NAME'];
			}
		}
		// Prevent multiple slashes to avoid cross site requests via the Form API.
		$uri = '/' . ltrim($uri, '/');
		
		return $uri;
	}

	/**
	 * Returns the Current URL.
	 *
	 * @return
	 * The current URL.
	 */
	protected function getCurrentUri() {
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
		$current_uri = $protocol . $_SERVER['HTTP_HOST'] . $this->getRequestUri();
		$parts = parse_url($current_uri);
		
		$query = '';
		if (!empty($parts['query'])) {
			$params = array();
			parse_str($parts['query'], $params);
			$params = array_filter($params);
			if (!empty($params)) {
				$query = '?' . http_build_query($params, NULL, '&');
			}
		}
		
		// Use port if non default.
		$port = '';
		if (isset($parts['port']) && (($protocol === 'http://' && $parts['port'] !== 80) || ($protocol === 'https://' && $parts['port'] !== 443))) {
			$port = ':' . $parts['port'];
		}
		
		
		// Rebuild.
		return $protocol . $parts['host'] . $port . $parts['path'] . $query;
	}

	/**
	 * Build the URL for given path and parameters.
	 *
	 * @param $path
	 * (optional) The path.
	 * @param $params
	 * (optional) The query parameters in associative array.
	 *
	 * @return
	 * The URL for the given parameters.
	 */
	protected function getUri($path = '', $params = array()) {
        $url = $this->_base_uri;
		if (!empty($path))
			if (substr($path, 0, 4) == "http")
				$url = $path;
			else
				$url = rtrim($url, '/') . '/' . ltrim($path, '/');
		
		if (!empty($params))
			$url .= '?' . http_build_query($params, NULL, '&');
		
		return $url;
	}

	/**
	 * Generate a signature for the given params and secret.
	 *
	 * @param $params
	 * The parameters to sign.
	 * @param $secret
	 * The secret to sign with.
	 *
	 * @return
	 * The generated signature
	 */
	protected function generateSignature($params, $secret) {
		// Work with sorted data.
		ksort($params);
		
		// Generate the base string.
		$base_string = '';
		foreach ( $params as $key => $value ) {
			$base_string .= $key . '=' . $value;
		}
		$base_string .= $secret;
		
		return md5($base_string);
	}
}/*}}}*/
