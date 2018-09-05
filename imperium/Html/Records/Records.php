<?php
/**
 * fumseck added HtmlTable.php to imperium
 * The 28/10/17 at 11
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package : imperium
 * @author  : fumseck
 */
namespace Imperium\Html\Records {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Tables\Table;


    class Records implements RecordsManagement
    {
        /**

         * @param string $html_table_class
         * @param Table $instance
         * @param string $current_table_name
         * @param string $edit_url_prefix
         * @param string $remove_url_prefix
         * @param string $action_edit_text
         * @param string $action_remove_text
         * @param string $edit_button_class
         * @param string $remove_button_class
         * @param string $edit_icon
         * @param string $remove_icon
         * @param int $limit_records_per_page
         * @param int $current_page
         * @param string $pagination_prefix_url
         * @param Connect $connect
         * @param string $action_save_text
         * @param string $confirm_before_remove_text
         * @param string $start_pagination_text
         * @param string $end_pagination_text
         * @param string $advanced_view_tab_text
         * @param string $simply_view_tab_text
         * @param string $form_prefix_url
         * @param string $table_view_tab_text
         * @param string $table_url_prefix
         * @param string $choose_text
         * @param bool $align_column_center
         * @param bool $column_to_upper
         * @param string $csrf_token_field
         * @param bool $pagination_to_right
         * @param bool $framework
         * @param bool $advanced_view_default
         * @param string $url_separator
         * @param int $textarea_row
         * @param string $table_icon
         * @param string $order_by
         *
         * @return string
         *
         * @throws Exception
         */
public static function show(
          string $html_table_class, Table $instance, string $current_table_name,
          string $edit_url_prefix, string $remove_url_prefix, string $action_edit_text,
          string $action_remove_text, string $edit_button_class, string $remove_button_class,
          string $edit_icon, string $remove_icon, int $limit_records_per_page, int $current_page,
          string $pagination_prefix_url, Connect $connect, string $action_save_text,
          string $confirm_before_remove_text, string $start_pagination_text,
          string $end_pagination_text, string $advanced_view_tab_text, string $simply_view_tab_text,
          string $form_prefix_url,string $table_view_tab_text,string $table_url_prefix, string $choose_text,bool $align_column_center,
          bool $column_to_upper,string $csrf_token_field = '', bool $pagination_to_right = true, bool $framework = false,
          bool $advanced_view_default = false,  string $url_separator = '/',int $textarea_row = 1, string $table_icon ='<i class="fas fa-table"></i>',string $order_by = 'desc'): string
{

            if ($framework)
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "/search/" + elem.value;}</script>';
            else
                $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}function search(elem){var pagination = elem.attributes[0].value;window.location = pagination + "?search=" + elem.value;}</script>';

            $records = get_records($instance,$current_table_name,$current_page,$limit_records_per_page,$connect,$framework,$order_by);

            $table_select = tables_select($instance,$table_url_prefix,$current_table_name,$choose_text,true,$csrf_token_field,$url_separator,$table_icon);

            $pagination =   pagination( $limit_records_per_page,$pagination_prefix_url,$current_page,$instance->count($current_table_name),$start_pagination_text,$end_pagination_text);

            if ($advanced_view_default)
            {
                append($html,'<ul class="nav nav-tabs mt-5" role="tablist"><li class="nav-item"><a class="nav-link active" id="'.$advanced_view_tab_text.'-tab" data-toggle="tab" href="#'.$advanced_view_tab_text.'" role="tab" aria-controls="'.$advanced_view_tab_text.'" aria-selected="true">'.$advanced_view_tab_text.'</a></li><li class="nav-item"><a class="nav-link" id="'.$simply_view_tab_text.'-tab" data-toggle="tab" href="#'.$simply_view_tab_text.'" role="tab" aria-controls="'.$simply_view_tab_text.'" aria-selected="false">'.$simply_view_tab_text.'</a></li><li class="nav-item"><a class="nav-link" id="'.$table_view_tab_text.'-tab" data-toggle="tab" href="#'.$current_table_name.'" role="tab" aria-controls="'.$table_view_tab_text.'" aria-selected="false">'.$table_view_tab_text.'</a></li></ul>',
                    '<div class="tab-content"><div class="tab-pane fade show active" id="'.$advanced_view_tab_text.'" role="tabpanel" aria-labelledby="'.$advanced_view_tab_text.'-tab">'.   advanced_view($current_table_name,$instance,$records,$form_prefix_url,$table_select,$action_save_text,$action_edit_text,$edit_button_class,$remove_url_prefix,$remove_button_class,$action_remove_text,$confirm_before_remove_text,$pagination,$align_column_center,$column_to_upper,$pagination_to_right,$csrf_token_field,$textarea_row)       .' </div>  <div class="tab-pane fade" id="'.$simply_view_tab_text.'" role="tabpanel" aria-labelledby="'.$simply_view_tab_text.'-tab">'. simply_view($current_table_name,$instance,$records,$table_select,$html_table_class,$action_remove_text,$confirm_before_remove_text,$remove_button_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_button_class,$edit_icon,$pagination,$align_column_center,$column_to_upper,$pagination_to_right ).'</div><div class="tab-pane fade" id="'.$table_view_tab_text.'" role="tabpanel" aria-labelledby="'.$table_view_tab_text.'-tab">'. tables_view($instance).'</div></div>');

            }
            else
            {
               append($html,'<ul class="nav nav-tabs mt-5" role="tablist"><li class="nav-item"><a class="nav-link active" id="'.$simply_view_tab_text.'-tab" data-toggle="tab" href="#'.$simply_view_tab_text.'" role="tab" aria-controls="'.$simply_view_tab_text.'" aria-selected="true">'.$simply_view_tab_text.'</a></li><li class="nav-item"><a class="nav-link" id="'.$advanced_view_tab_text.'-tab" data-toggle="tab" href="#'.$advanced_view_tab_text.'" role="tab" aria-controls="'.$advanced_view_tab_text.'" aria-selected="false">'.$advanced_view_tab_text.'</a></li><li class="nav-item"><a class="nav-link" id="'.$table_view_tab_text.'-tab" data-toggle="tab" href="#'.$table_view_tab_text.'" role="tab" aria-controls="'.$table_view_tab_text.'" aria-selected="false">'.$table_view_tab_text.'</a></li></ul>',
                   '<div class="tab-content"><div class="tab-pane fade show active" id="'.$simply_view_tab_text.'" role="tabpanel" aria-labelledby="'.$simply_view_tab_text.'-tab">'. simply_view($current_table_name,$instance,$records,$table_select,$html_table_class,$action_remove_text,$confirm_before_remove_text,$remove_button_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_button_class,$edit_icon,$pagination,$align_column_center,$column_to_upper,$pagination_to_right ).' </div>  <div class="tab-pane fade" id="'.$advanced_view_tab_text.'" role="tabpanel" aria-labelledby="'.$advanced_view_tab_text.'-tab">'.        advanced_view($current_table_name,$instance,$records,$form_prefix_url,$table_select,$action_save_text,$action_edit_text,$edit_button_class,$remove_url_prefix,$remove_button_class,$action_remove_text,$confirm_before_remove_text,$pagination,$align_column_center,$column_to_upper,$pagination_to_right,$csrf_token_field,$textarea_row)  .'</div><div class="tab-pane fade" id="'.$table_view_tab_text.'" role="tabpanel" aria-labelledby="'.$table_view_tab_text.'-tab">'.tables_view($instance).'</div></div>');
            }

            return $html;
        }
    }
}
