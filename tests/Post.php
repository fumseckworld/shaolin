<?php


namespace Testing {


    use Imperium\Html\Icon\Icon;

    class Post
    {
        use Share;


        public function show(int $id)
        {
           return $this->view->load('welcome.twig',compact('id'));
        }

        public function home()
        {
            $table = (new Icon('list-unstyled list-inline','list-inline-item','btn btn-primary'))->add(fa('fas','fa-linux'),'/','a')->add('a','a','b')->generate();
            return $this->view->load('welcome.twig',compact('table'));

        }
        public function trans()
        {
            phpinfo();
             $this->view->load('trans.twig');
        }
    }
}