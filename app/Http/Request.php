<?php namespace Prontotype\Http;

use Amu\SuperSharp\Http\Request as SuperSharpRequest;
use Dflydev\ApacheMimeTypes\PhpRepository as MimeRepo;

class Request extends SuperSharpRequest
{
    public function getRequestMime()
    {
        $mimes = new MimeRepo();
        $ext = strtolower(pathinfo($this->getRequestUri(), PATHINFO_EXTENSION)) ?: 'html';
        return $mimes->findType($ext);
    }
}