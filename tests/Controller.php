<?php


namespace Testing {


    use Imperium\Controller\BaseController;

    class Controller extends BaseController
    {

        /**
         * @param string $table
         * @param int $id
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function remove(string $table,int $id)
        {
            if ($this->table()->from($table)->remove($id))
                $this->flash()->success('removed');
            else
                $this->flash()->failure('failure');

            return redirect('home');
        }

        /**
         * @return string
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         * @throws \Exception
         */
        public function show()
        {
            $table = current_table();
            $code = html('div',$this->model()->show('table-responsive','thead-dark','?current',1,'table','remove','sure',fa('fas','fa-trash'),"remove/$table",'edit','edit',
                fa('fas','fa-edit'),'start','previous','id','desc','search',fa('fas','fa-table'),fa('fas','fa-search'),fa('fas','fa-anchor"')),'container');

            return view('welcome.twig',compact('code'));

        }

        public function display(int $id,string $slug)
        {
            return "$id $slug";
        }
    }
}