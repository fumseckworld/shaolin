<?php
/**
 * fumseck added Create.php to imperium
 * The 09/09/17 at 13:30
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
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Html\Form\Core {

    use Exception;
    use Imperium\Databases\Eloquent\Tables\Table;
    use Imperium\Html\Form\Form;


    /**
     * Interface FormBuilder
     *
     * @package Imperium\Form\Core
     */
    interface FormBuilder
    {
        /**
         * start the form
         *
         * @param string $action
         * @param string $id
         * @param string|null $class
         * @param bool $enctype
         * @param string $method
         * @param string $charset
         *
         * @return Form
         */
        public function start(string $action,string $id,string $class = '', bool $enctype = false, string $method = Form::POST, string $charset = 'utf8'): Form;

        /**
         * generate a form
         *
         * @param string $table
         * @param Table $instance
         * @param string $submitText
         * @param string $submitClass
         * @param string $submitId
         * @param string $submitIcon
         * @param int $mode
         * @param int $id
         *
         * @return string
         */
        public function generate(string $table, Table $instance,string $submitText,string $submitClass,string $submitId,string $submitIcon = '',int $mode = Form::CREATE,int $id = 0): string;

        /**
         * generate an advanced records view
         *
         * @param string $table
         * @param Table $instance
         * @param array $records
         * @param string $action
         * @param string $tableClass
         * @param string $searchPlaceholder
         * @param string $tableUrlPrefix
         * @param int $limit
         * @param string $removeUrl
         * @param string $removeClassBtn
         * @param string $removeText
         * @param string $confirmRemoveText
         * @param string $removeIcon
         * @param string $pagination
         * @param bool $columnAlignCenter
         * @param bool $columnToUpper
         * @param bool $paginationPreferRight
         * @param string $csrf
         * @param int $textareaCols
         * @param int $textareaRow
         * @return string
         */
        public function generateAdvancedRecordView(string $table, Table $instance , array $records , string $action, string $tableClass, string $searchPlaceholder, string $tableUrlPrefix, int $limit, string $removeUrl,string $removeClassBtn,string $removeText,string $confirmRemoveText,string $removeIcon,string $pagination,bool $columnAlignCenter,bool $columnToUpper,bool $paginationPreferRight,string $csrf ='', int $textareaCols = 25, int  $textareaRow =  1): string;

        /**
         * generate a simply record view
         *
         * @param string $table
         * @param Table $instance
         * @param array $records
         * @param string $action
         * @param string $tableClass
         * @param string $searchPlaceholder
         * @param string $tableUrlPrefix
         * @param int $limit
         * @param string $removeText
         * @param string $removeConfirm
         * @param string $removeBtnClass
         * @param string $removeUrl
         * @param string $removeIcon
         * @param string $editText
         * @param string $editUrl
         * @param string $editClass
         * @param string $editIcon
         * @param string $pagination
         * @param bool $columnAlignCenter
         * @param bool $columnToUpper
         * @param bool $preferPaginationRight
         * @return string
         */
        public function generateSimplyRecordView(string $table, Table $instance , array $records , string $action, string $tableClass, string $searchPlaceholder, string $tableUrlPrefix, int $limit,string $removeText,string $removeConfirm,string $removeBtnClass,string $removeUrl,string $removeIcon,string $editText,string $editUrl,string $editClass,string $editIcon,string $pagination,bool $columnAlignCenter,bool $columnToUpper,bool $preferPaginationRight = true): string;


        /**
         * generate alter table view
         *
         * @param string $table
         * @param Table $instance
         * @param int $formType
         *
         * @return string
         */
        public function generateAlterTableView(string $table, Table $instance,int $formType = Form::BOOTSTRAP): string;

        /**
         * start hidden input
         *
         * @return Form
         */
        public function startHide(): Form;

        /**
         * close hidden input
         *
         * @return Form
         */
        public function endHide(): Form;

        /**
         * generate a files input
         *
         * @param string      $name
         * @param string      $class
         * @param string      $text
         * @param string      $ico
         *
         * @return Form
         */
        public function file(string $name, string $class, string $text, string $ico = ''): Form;

        /**
         * define input size to large
         *
         * @param bool $large
         *
         * @return Form
         */
        public function setLargeInput(bool $large): Form;

        /**
         * define input size to small
         *
         * @param bool small
         *
         * @return Form
         */
        public function setSmallInput(bool $small): Form;

        /**
         * generate an input
         *
         * @param string $type
         * @param string $name
         * @param string $placeholder
         * @param string $icon
         * @param string $value
         * @param bool $required
         * @param bool $autofocus
         * @param bool $autoComplete
         * @param bool $tableOption
         * @param string $dataFormId
         * @return Form
         */
        public function input(string $type, string $name, string $placeholder, string $icon = '', string $value = '', bool $required = true  , bool $autofocus = false, bool $autoComplete = false,bool $tableOption = false,string $dataFormId =''): Form;

        /**
         * set form type
         *
         * @param int $type
         *
         * @return Form
         */
        public function setType(int $type): Form;

        /**
         * generate two inline input
         *
         * @param string $typeOne
         * @param string $nameOne
         * @param string $placeholderOne
         * @param string $valueOne
         * @param string $iconOne
         * @param bool   $requiredOne
         * @param string $typeTwo
         * @param string $nameTwo
         * @param string $placeholderTwo
         * @param string $valueTwo
         * @param string $iconTwo
         * @param bool   $requiredTwo
         *
         * @return Form
         */
        public function twoInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo): Form;

        /**
         * generate three inline input
         *
         * @param string $typeOne
         * @param string $nameOne
         * @param string $placeholderOne
         * @param string $valueOne
         * @param string $iconOne
         * @param bool $requiredOne
         * @param string $typeTwo
         * @param string $nameTwo
         * @param string $placeholderTwo
         * @param string $valueTwo
         * @param string $iconTwo
         * @param bool $requiredTwo
         * @param string $typeThree
         * @param string $nameThree
         * @param string $placeholderThree
         * @param string $valueThree
         * @param string $iconThree
         * @param bool $requiredThree
         *
         * @return Form
         */
        public function threeInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree): Form;

        /**
         * generate four inline input
         *
         * @param string $typeOne
         * @param string $nameOne
         * @param string $placeholderOne
         * @param string $valueOne
         * @param string $iconOne
         * @param bool $requiredOne
         * @param string $typeTwo
         * @param string $nameTwo
         * @param string $placeholderTwo
         * @param string $valueTwo
         * @param string $iconTwo
         * @param bool $requiredTwo
         * @param string $typeThree
         * @param string $nameThree
         * @param string $placeholderThree
         * @param string $valueThree
         * @param string $iconThree
         * @param bool $requiredThree
         * @param string $typefour
         * @param string $nameFour
         * @param string $placeholderFour
         * @param string $valueFour
         * @param string $iconFour
         * @param bool $requiredFour
         *
         * @return Form
         */
        public function fourInlineInput(string $typeOne, string $nameOne, string $placeholderOne, string $valueOne, string $iconOne, bool $requiredOne, string $typeTwo, string $nameTwo, string $placeholderTwo, string $valueTwo, string $iconTwo, bool $requiredTwo, string $typeThree, string $nameThree, string $placeholderThree, string $valueThree, string $iconThree, bool $requiredThree, string $typefour, string $nameFour, string $placeholderFour, string $valueFour, string $iconFour, bool $requiredFour): Form;

        /**
         * generate four select
         *
         * @param string $nameOne
         * @param array $optionsOne
         * @param string $iconOne
         * @param string $nameTwo
         * @param array $optionsTwo
         * @param string $iconTwo
         * @param string $nameThree
         * @param array $optionsThree
         * @param string $iconThree
         * @param string $nameFour
         * @param array $optionsFour
         * @param string $iconFour
         *
         * @return Form
         */
        public function fourInlineSelect(string $nameOne,array $optionsOne,string $iconOne,string $nameTwo,array $optionsTwo,string $iconTwo,string $nameThree,array $optionsThree,string $iconThree,string $nameFour,array $optionsFour,string $iconFour): Form;

        /**
         * add csrf token in form
         *
         * @param string $csrf
         *
         * @return Form
         */
        public function csrf(string $csrf): Form;

        /**
         * generate a button
         *
         * @param string      $text
         * @param string      $class
         * @param string      $icon
         * @param string      $type
         *
         * @return Form
         */
        public function button(string $text, string $class, string $icon = '',string $type = Form::BUTTON ): Form;

        /**
         * generate a button to reset the form
         *
         * @param string      $text
         * @param string      $class
         * @param string|null $icon
         *
         * @return Form
         */
        public function reset(string $text, string $class, string $icon = ''):Form;

        /**
         * generate a textarea
         *
         * @param string $name
         * @param string $placeholder
         * @param int $cols
         * @param int $row
         * @param bool $autofocus
         * @param string $value
         * @param bool $tableOption
         * @param string $dataFormId
         *
         * @return Form
         */
        public function textarea(string $name, string $placeholder, int $cols, int $row,bool $autofocus = false,string $value = '',bool $tableOption = false,string $dataFormId=''): Form;

        /**
         * generate a image
         *
         * @param string      $src
         * @param string      $alt
         * @param string      $class
         * @param string      $width
         *
         * @return Form
         */
        public function img(string $src, string $alt, string $class = '', string $width = '100%'): Form;

        /**
         * call form builder
         *
         * @return Form
         */
        public static function create(): Form;

        /**
         * generate a submit button
         *
         * @param string $text
         * @param string $class
         * @param string $id
         * @param string $icon
         *
         * @return Form
         */
        public function submit(string $text, string $class, string $id,string $icon = ''): Form;

        /**
         * generate a link
         *
         * @param string      $url
         * @param string      $class
         * @param string      $text
         * @param string|null $icon
         *
         * @return Form
         */
        public function link(string $url, string $class, string $text, string $icon = ''): Form;

        /**
         * generate a select input
         *
         * @param string $name
         * @param array $options
         * @param string|null $icon
         *
         * @param bool $multiple
         * @return Form
         */
        public function select(string $name, array $options,string $icon = '',bool $multiple = false): Form;

        /**
         * @param string $nameOne
         * @param array  $optionsOne
         * @param string $iconOne
         * @param string $nameTwo
         * @param array  $optionsTwo
         * @param string $iconTwo
         *
         * @return Form
         */
        public function twoInlineSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo):Form;

        /**
         * generate a checkbox input
         *
         * @param string $name
         * @param string $text
         * @param string $class
         * @param bool   $checked
         *
         * @return Form
         */
        public function checkbox(string $name, string $text, string $class, bool $checked = false): Form;

        /**
         * generate a radio input
         *
         * @param string $name
         * @param string $text
         * @param string $class
         * @param bool   $checked
         *
         * @return Form
         */
        public function radio(string $name, string $text, string $class, bool $checked = false): Form;

        /**
         * return the form
         *
         * @return string
         */
        public function end(): string;

        /**
         * generate a redirect select
         *
         * @param string $name
         * @param array  $options
         * @param string $icon
         *
         * @return Form
         */
        public function redirectSelect(string $name, array $options, string $icon = ''): Form;

        /**
         * generate two inline redirect select
         *
         * @param string $nameOne
         * @param array  $optionsOne
         * @param string $iconOne
         * @param string $nameTwo
         * @param array  $optionsTwo
         * @param string $iconTwo
         *
         * @return Form
         * @throws Exception
         */
        public function twoRedirectSelect(string $nameOne, array $optionsOne, string $iconOne, string $nameTwo, array $optionsTwo, string $iconTwo): Form;

        /**
         * generate one select and one input inline
         *
         * @param string      $selectName
         * @param array       $selectOptions
         * @param string      $selectIconOne
         * @param string      $type
         * @param string      $name
         * @param string      $placeholder
         * @param bool        $required
         * @param string      $iconTwo
         * @param string      $value
         *
         * @return Form
         */
        public function oneSelectOneInput(string $selectName, array $selectOptions, string $selectIconOne, string $type, string $name, string $placeholder, bool $required , string $iconTwo, string $value): Form;

        /**
         * generate one input and one select
         *
         * @param string      $type
         * @param string      $name
         * @param string      $placeholder
         * @param bool        $required
         * @param string      $inputIcon
         * @param string      $value
         * @param string      $selectName
         * @param array       $selectOptions
         * @param string      $selectIconOne
         *
         * @return Form
         */
        public function oneInputOneSelect(string $type, string $name, string $placeholder, bool $required, string $inputIcon, string $value , string $selectName, array $selectOptions, string $selectIconOne): Form;
    }
}