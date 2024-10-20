<?php
    class Controller
    {
        public function  model($model)
        {
            $linkmodel = __DIR__."/../model/".$model.".php";
            if (file_exists($linkmodel)) {
                require_once $linkmodel;
                
                // Check if the class exists after requiring
                if (class_exists($model)) {
                    return new $model();
                } else {
                    throw new Exception("Model class '$model' does not exist.");
                }
            } else {
                throw new Exception("Model file '$linkmodel' does not exist.");
            }
        }
        public function view($folder=null,$view,$data=[])
        {
            $linkview = __DIR__."/../view/".$folder."/".$view.".php";
            if (file_exists($linkview)) {
                // Extract data to make it available in the view
                extract($data);
                
                require_once $linkview;
            } else {
                throw new Exception("View file '$linkview' does not exist.");
            }
        }
    }