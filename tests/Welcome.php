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
         * @param int $x
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function pagination(int $x)
        {
            $this->session()->set($x,'limit');
            return back();
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

        /**
         * @param string $table
         * @param int $id
         *
         * @return string
         *
         * @throws \Exception
         */
        public function edit(string $table,int $id): string
        {
            $edit = edit($table,$id,url('update',POST),id(),'update','');
            $create = create($table,name('create',POST),'','create',fa('fas','fa-plus'));
            return $this->view('edit',compact('edit','create'));
        }


        /**
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         *
         * @throws \Exception
         */
        public function create()
        {
            $bool = $this->model()->add();
            $message  = $bool ? 'success' : 'failure';
            return back($message,$bool);
        }

        /**
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function del()
        {
            $message = $this->table()->drop(\request()->get('table')) ? "table removed" : "Failure";

            return back($message);

        }

        /**
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function update()
        {
            $bool = $this->model()->update();
            $message = $bool ? 'success' : 'failure';
           return back($message,$bool);
        }
    }
}