<?php namespace Prontotype\Http;

use Amu\SuperSharp\Http\Request as SuperSharpRequest;
use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

class Request extends SuperSharpRequest
{
    protected $urlSegments = null;

    public function getRequestMime()
    {
        $mimes = new MimeRepo();
        $ext = strtolower(pathinfo($this->getRequestUri(), PATHINFO_EXTENSION)) ?: 'html';
        return $mimes->findType($ext);
    }

    public function getUrlSegments()
    {
        if ( $this->urlSegments !== null ) {
            return $this->urlSegments;  
        }
        
        $path = trim($this->getUrlPath(),'/');
        
        if ( ! empty($path) ) {
            $this->urlSegments = explode('/', $path);            
        } else {
            $this->urlSegments = array();
        }
        
        return $this->urlSegments;
    }
    
    public function getUrlSegment($pos)
    {
        $segments = $this->getUrlSegments();
        return isset($segments[$pos]) ? $segments[$pos] : null;
    }
    
    public function getUrlPath($stripTrailingSlash = true)
    {
        list($path) = explode('?', $this->getRequestUri());
        if ( $stripTrailingSlash ) {
            $path = rtrim($path,'/');
        }
        return $path;
    }

    public function getQueryParam($param)
    {
        return $this->query->get($param);
    }
    
    public function getQueryParams()
    {
        return $this->query;
    }
    
    public function getPostParam($param)
    {
        return $this->request->get($param);
    }
    
    public function getPostParams()
    {
        return $this->request;
    }

    public function __toString()
    {
        return $this->getUrlPath();
    }
    
}