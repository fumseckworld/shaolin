<?php


namespace Testing {


    use Imperium\Html\Table\Table;

    class Post
    {
        use Share;


        public function show(int $id)
        {
           return $this->view->load('welcome.twig',compact('id'));
        }

        public function home()
        {
            $app = app('mysql','root','zen','root','localhost','../dump','imperium','..','..',[],[],[]);

            $table = Table::table($app->tables()->from('imperium')->columns(),$app->tables()->from('imperium')->all())->generate('table table-bordered table-danger table-hover ' );
            return $this->view->load('welcome.twig',compact('table'));

        }
        public function trans()
        {
            phpinfo();
             $this->view->load('trans.twig');
        }
    }
}