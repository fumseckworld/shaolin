<?php


namespace Testing {



    class Post
    {
        use Share;

        public function show(int $id)
        {
           return $this->view->load('welcome.twig',compact('id'));
        }

        public function trans()
        {
            phpinfo();
             $this->view->load('trans.twig');
        }
    }
}