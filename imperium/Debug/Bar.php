<?php


namespace Imperium\Debug;




use Imperium\App;
use Imperium\Exception\Kedavra;

class Bar
{


    /**
     * @param App $app
     * @return string
     * @throws Kedavra
     */
    public function render(App $app):string
    {
        if (!$app->debug())
            return '';

        $time = $app->request_time();

        return  '<nav class="navbar navbar-expand-lg fixed-bottom mt-5" id="debug_bar">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav mr-auto">
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">laptop</i> 
                <span class="icon-text">'.os(true).'</span>
            </li>
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">check</i> 
                <span class="icon-text">'.$app->response()->getStatusCode().'</span>
            </li> 
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">timer</i> 
                <span class="icon-text">'.$time.' ms</span>
            </li> 
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                 <i class="material-icons">trip_origin</i> 
                <span class="icon-text">'.$app->git('.','')->current_branch().'</span>
            </li>   
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">my_location</i> 
                <span class="icon-text">'.$app->route_result()->controller().'</span>
            </li>   
            
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">my_location</i> 
                <span class="icon-text">'.$app->route_result()->action().'</span>
            </li>   
              
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">bookmarks</i> 
                <span class="icon-text">'.$app->session()->get('view').'</span>
            </li> 
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">language</i> 
                <span class="icon-text">'.$app->lang().'</span>
            </li>
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">storage</i> 
                <span class="icon-text">'.$app->connect()->base().'</span>
            </li>    
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">layers</i> 
                <span class="icon-text">'.$app->table()->found().'</span>
            </li>    
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">memory</i> 
                <span class="icon-text">'.memory().'</span>
            </li>           
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">people</i> 
                <span class="icon-text">'.$app->model()->count('users').'</span>
            </li>     
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <i class="material-icons">perm_identity</i> 
                 <span class="icon-text">'.$app->auth()->connected_username().'</span>
            </li>     
           
            
        </ul>
         <ul class="navbar-nav ml-auto">
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <a href="/" onclick="window.location.reload()" class="icon-text"><i class="material-icons">update</i> </a>
            </li>
             <li class="icon-bar font-weight-bold ml-2 mr-2">
                <a href="'.admin().'" class="icon-text" ><i class="material-icons">developer_board</i> </a>
            </li>  
              <li class="icon-bar font-weight-bold ml-2 mr-2">
                <a href="'.route('app').'" class="icon-text"><i class="material-icons">apps</i> </a>
            </li>  
            <li class="icon-bar font-weight-bold ml-2 mr-2">
                <a href="'.route('logout').'" onclick="window.location.reload()" class="icon-text"><i class="material-icons">power_settings_news</i> </a>
            </li>            
        </ul>
    </nav>';


    }
}