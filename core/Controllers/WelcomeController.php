<?php
namespace  Shaolin\Controllers;



    use Exception;
    use Imperium\Controller\Controller;
    use Imperium\Request\Request;

    class WelcomeController extends Controller
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
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws Exception
         */
        public function logout()
        {
            return $this->auth()->logout();
        }

        /**
         * @param int $x
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function pagination(int $x)
        {
            $this->session()->set('limit',$x);
            return back();
        }

        /**
         * @return string
         * @throws Exception
         */
        public function home()
        {
            $table = current_table();

            $code = $this->model()->show('table-responsive','thead-dark','?current',1,'table','remove','sure',fa('fas','fa-trash'),"remove/$table",'edit',"edit/$table",
                fa('fas','fa-edit'),'start','previous','id','desc',name('home'),'manage','search',fa('fas','fa-table'),fa('fas','fa-search'),fa('fas','fa-anchor'));

           $del =  form(name('del',POST),'delete')->row()->select(false,'table',$this->table()->show())->end_row_and_new()->submit('a','a')->end_row()->get();

           $query = query_view('sure',name('query',POST),name('create',POST),'create',$table,'expected','submit','reset');


           return $this->view(__FUNCTION__,compact('code','del','query'));

        }


        /**
         * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
         * @throws Exception
         */
        public function display()
        {

            $sql = '';

           $data =  execute_query($sql);

           if (is_bool($data))
               return is_true($data) ? back('success') : back('failure',false);


           $data = $this->model()->parse($data,name('update',POST),'update',Request::get('__table__'),Request::get('primary'));
           return $this->view(__FUNCTION__,compact('data','sql'));
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
            $edit = edit($table,$id,name('update',POST),id(),'update','');
            $create = create($table,name('create',POST),'','create',fa('fas','fa-plus'));
            return $this->view(__FUNCTION__,compact('edit','create'));
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
           return to('/',$message,$bool);
        }
}