<?php


namespace Testing {


    use Imperium\Controller\Controller;
    use Imperium\Request\Request;

    class Welcome extends Controller
    {

        /**
         * @param string $table
         * @param int $id
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function remove(string $table,int $id)
        {
            $message = $this->table()->from($table)->remove($id) ? "The record in the $table $id was removed successfully" : "Failure";

            return redirect('home',$message);
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

            $code = html('div',$this->model()->show('table-responsive','thead-dark','?current',1,'table','remove','sure',fa('fas','fa-trash'),"remove/$table",'edit',"edit/$table",
                fa('fas','fa-edit'),'start','previous','id','desc','search',fa('fas','fa-table'),fa('fas','fa-search'),fa('fas','fa-anchor"')),'container');

           $del =  form(url('del',POST),'a')->select(false,'table',$this->table()->show())->submit('a','a')->get();
            return view('welcome',compact('code','del'));

        }

        public function display(int $id,string $slug)
        {
            return "$id $slug";
        }

        public function edit(string $table,int $id)
        {
            $form = edit($table,$id,'/',id(),'update','');
            return view('edit',compact('form'));
        }

        public function del()
        {
            d(\request()->get('table'));

            d(Request::all());
        }
    }
}