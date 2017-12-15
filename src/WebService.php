<?php 

namespace Yanlinwang\GaodeMaps;

/**
 * Description of GaodeMaps
 *
 * @author Yan Lin Wang <charles.w.developer@gmail.com>
 */

//use \GaodeMaps\Parameters;

class WebService
{
    /*
    |--------------------------------------------------------------------------
    | Web Service
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $service;    
    
    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $key;     
    
    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $requestUrl; 
    
    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $verifySSL;        

    /**
     * Class constructor
     */
    public function __construct()
    { 
        
    }
    
    /**
     * Set parameter by key
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParamByKey($key, $value)
    {
         if(array_key_exists($key, array_dot($this->service['param'])))
         {
             array_set($this->service['param'], $key, $value);
         }  
         
         return $this;
    }
    
    /**
     * Get parameter by the key
     * @param string $key
     * @return mixed
     */ 
    public function getParamByKey($key)
    {
         if(array_key_exists($key, array_dot($this->service['param'])))
         {
             return array_get($this->service['param'], $key);
         }
    }
    
    /**
     * Set all parameters at once
     * @param array $param
     * @return $this
     */
    public function setParam($param)
    {
        $this->service['param'] = array_merge($this->service['param'], $param);
        
        return $this;
    }

    /**
     * Return parameters array
     * @return array
     */
    public function getParam()
    {
        return $this->service['param'];
    }
    
    /**
     * Get Web Service Response
     * @param string $needle - response key
     * @return string
     */
    public function get($needle = false)
    {
        return empty($needle)
                ? $this->getResponse()
                : $this->getResponseByKey($needle);
    }

    /**
     * Post JSON to Web Service
     * @return type
     */
    public function post()
    {
        return $this->make(json_encode($this->service['param']));
    }
    
    /**
     * Get response value by key
     * @param string $needle - retrieves response parameter using "dot" notation
     * @param int $offset 
     * @param int $length
     * @return array
     */
    public function getResponseByKey($needle = false, $offset = 0, $length = null)
    {
        // set default key parameter
        $needle = empty($needle) 
                    ? metaphone($this->service['responseDefaultKey']) 
                    : metaphone($needle);
        
        // get response
        $obj = json_decode($this->get(), true);
            
        // flatten array into single level array using 'dot' notation
        $obj_dot = array_dot($obj);
        // create empty response
        $response = [];
        // iterate 
        foreach($obj_dot as $key => $val)
        {
            // Calculate the metaphone key and compare with needle
            if(strcmp(metaphone($key, strlen($needle)), $needle) === 0)
            {
                // set response value
                array_set($response, $key, $val);
            }
        }

        // finally extract slice of the array
        #return array_slice($response, $offset, $length);

        return count($response) < 1
               ? $obj
               : $response;
    }
    
    /**
     * Get response status
     * @return mixed
     */
    public function getStatus()
    {
        // get response
        $obj = json_decode($this->get(), true);
        
        return array_get($obj, 'status', null);
    }

    /**
     * Get full request url for batched request without touching protected $requestUrl
     * @return string $url, full request url
     */
    public function getBatchUrl()
    {
        $url = $this->requestUrl . '?key=' .urlencode($this->key);
        
        switch($this->service['type'])
        {
            case 'POST':
                $post =  json_encode($this->service['param']);
                break;
            case 'GET':
            default:
                $url .= '&' . Parameters::getQueryString($this->service['param']);
                break;
        }

        // strip away domain
        $url = parse_url($url, PHP_URL_PATH) . '?' . parse_url($url, PHP_URL_QUERY);
        
        return $url;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */     
   
    /**
     * Setup service parameters
     */
    protected function build($service)
    {
        $this->validateConfig($service);
        
        // set web service parameters 
        $this->service = config('gaodemaps.service.'.$service);
        
        // is service key set, use it, otherwise use default key
        $this->key = empty($this->service['key'])
                        ? config('gaodemaps.key')
                        : $this->service['key'];
        
        // set service url
        $this->requestUrl = $this->service['url'];
        
        // is ssl_verify_peer key set, use it, otherwise use default key
        $this->verifySSL = empty(config('gaodemaps.ssl_verify_peer')) 
                        ? FALSE
                        : config('gaodemaps.ssl_verify_peer');

        $this->clearParameters();
    }
    
    /**
     * Validate configuration file
     * @throws \ErrorException
     */ 
    protected function validateConfig($service)
    {
        // Check for config file
        if(!\Config::has('gaodemaps'))
        {
            throw new \ErrorException('Unable to find config file.');
        }
        
        // Validate Key parameter
        if(!array_key_exists('key', config('gaodemaps')))
        {
            throw new \ErrorException('Unable to find Key parameter in configuration file.');
        }
        
        // Validate Key parameter
        if(!array_key_exists('service', config('gaodemaps'))
                && !array_key_exists($service, config('gaodemaps.service')))
        {
            throw new \ErrorException('Web service must be declared in the configuration file.');
        }
    }

    /**
     * Get Web Service Response
     * @return type
     */
    protected function getResponse()
    {
        $post = false;
        
        // set API Key
        $this->requestUrl .= '?key=' .urlencode($this->key);
        switch($this->service['type'])
        {
            case 'POST':
                $post =  json_encode($this->service['param']);
                break;
            case 'GET':
            default:
                $this->requestUrl .= '&' . Parameters::getQueryString($this->service['param']);
                break;
        }
        
        return $this->make($post);
    }
    
    /**
     * Make cURL request to given URL
     * @param boolean $isPost
     * @return object
     */
    protected function make($isPost = false)
    {
        $ch = curl_init($this->requestUrl);
       
       if($isPost)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json;charset=UTF-8',
                'Content-Length: ' . strlen($isPost)
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $isPost);
        }
       
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $output = curl_exec($ch);
       
        if($output === false)
        {
            throw new \ErrorException(curl_error($ch));
        }

        curl_close($ch);
        return $output;
    }

    protected function clearParameters()
    {
        Parameters::resetParams();
    }
}
