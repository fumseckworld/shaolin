<?php


namespace Eywa\Session {


    use Eywa\Exception\Kedavra;

    class Flash extends Session
    {

        /**
         * Diplay
         *
         * @return string
         *
         * @throws Kedavra .
         *
         */
        public function display(): string
        {


            if (php_sapi_name() !== 'cli')
            {
                $success_class = config('flash','success_class');

                $failure_class = config('flash','failure_class');


                $result = '';

                foreach ([SUCCESS,FAILURE] as $key)
                {
                    $success = equal($key,SUCCESS);
                    $message = $this->get($key);
                    $this->destroy($key);

                    if(def($message))
                    {
                        return $success ? '<div class="'.$success_class.'" role="alert">'.$message.'</div>':'<div class="'.$failure_class.'" role="alert">'.$message.'</div>';
                    }


                }
                return $result;
            }
            return  '';

        }
    }
}