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

            $code = $this->model()->show('table-responsive','thead-dark','?current',1,'table','remove','sure',fa('fas','fa-trash'),"remove/$table",'edit',"edit/$table",
                fa('fas','fa-edit'),'start','previous','id','desc',name('home'),'manage','search',fa('fas','fa-table'),fa('fas','fa-search'),fa('fas','fa-anchor'));

           $del =  form(url('del',POST),'delete')->select(false,'table',$this->table()->show())->submit('a','a')->get();

           $query = query_view('sure',name('query',POST),name('create',POST),'create',$table,'expected','submit','reset');
           return view('welcome',compact('code','del','query'));

        }

        public function check()
        {
            $pass = Request::get('password');
            $username   = Request::get('username');

            return $this->auth()->redirect_url(url('home','GET',true))->login($username,$pass);
        }
        /**
         * @return string
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        public function login()
        {
            $form = login('/login',id(),'username','password','login','a');

            return view('login',compact('form'));
        }
        public function display()
        {

            $sql = '';

           $data =  execute_query($sql);

           if (is_bool($data))
               return is_true($data) ? back('success') : back('failure',false);


           $data = $this->model()->parse($data,name('update',POST),'update',Request::get('__table__'),Request::get('primary'));
           return view('show',compact('data','sql'));
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
           return to('/',$message,$bool);
        }
    }
}