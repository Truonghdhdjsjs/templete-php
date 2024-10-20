<?php
    function debug($fix)
    {
            echo "<pre>";
            print_r($fix);
            echo"</pre>";
            die;
    }   
    function gettemplete($templete)
    {
        $link = __DIR__."/../include/".$templete.".php";
        if(file_exists($link))
        {
            require_once $link;
        }
        else
        {
            throw new Exception("Error file {$templete}", 1);
            
        }
    }