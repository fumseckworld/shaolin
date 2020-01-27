<?php
declare(strict_types=1);

namespace Eywa\Action {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Response;


    /**
     * Class Crud
     *
     * @author  Willy Micieli
     *
     * @package Imperium\Crud
     *
     * @license GPL
     *
     * @version 10
     *
     *
     */
    abstract class Crud extends Controller
    {

        /**
         *
         * The created success message
         *
         */
        private string $created;

        /**
         *
         * The no created message
         *
         */
        private string $no_created;

        /**
         *
         * The updated success message
         *
         */
        private string $updated;

        /**
         *
         * The not update failure message
         *
         */
        private string $no_updated;

        /**
         *
         * The deleted successfully message
         *
         */
        private string $deleted;

        /**
         *
         * The not deleted message
         *
         */
        private string $no_deleted;

        /**
         *
         * The pagination limit
         *
         */
        protected static int $limit = 20;


        /**
         *
         * The current table
         *
         */
        private string $current;

        /**
         *
         * The failure truncate message
         *
         */
        private string $no_truncated;

        /**
         *
         * The truncated table success message
         *
         */
        private string $truncated;


        /**
         *
         * Create a new record
         *
         * @param string $table
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function add(string $table): RedirectResponse
        {

            $this->init();

            $sql = $this->sql($table);

            $columns = $sql->columns();

            $x = collect($columns)->join();

            $values = collect(request()->request->all())->del(CSRF_TOKEN)->for('htmlentities')->all();

            $id = $sql->primary();

            $query = "INSERT INTO $table ($x) VALUES (";

            foreach ($columns as $column)
                equal($column, $id) ? $sql->connexion()->postgresql() ? append($query, 'DEFAULT, ') : append($query, 'NULL, ') : append($query, $sql->connexion()->pdo()->quote(collect($values)->get($column)) . ', ');

            $query = trim($query, ', ');

            append($query, ')');

            return $sql->connexion()->execute($query) ? back($this->created) : back($this->no_created, false);

        }

        /**
         *
         * Read all record with a pagination
         *
         * @param string $table
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function show(string $table): Response
        {
            $this->current = $table;

            $all = '<div class="table-responsive"><table class="table table-bordered"><thead>';
            foreach ($this->sql($table)->columns() as $column)
                append($all, "<th>$column</th>");

            append($all, '</thead><tbody>');

            $x = $this->sql($table)->paginate([$this, 'display'], $this->get('page', 1), static::$limit);
            append($all, $x->content());
            append($all, '</tbody></table></div>');
            append($all, $x->pagination());
            return $this->view('@crud/show','All records','Display contents' ,compact('all', 'table'))->render();
        }

        /**
         *
         * Truncate a table
         *
         * @param string $table
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function clear(string $table): RedirectResponse
        {
            $this->init();

            return $this->truncate($table) ? $this->to(route('admin', 'show', [$table]), $this->truncated) : $this->to(route('admin', 'show', [$table]), $this->no_truncated, false);
        }

        /**
         *
         * Generate a form to update a record
         *
         * @param string $table
         * @param int $id
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws LoaderError
         * @throws NotFoundException
         * @throws RuntimeError
         * @throws SyntaxError
         *
         */
        public function edit(string $table, int $id): Response
        {
            $form = $this->form(POST, 'admin', 'update', $table, $id)->edit($table, $id);
            return $this->view('@crud/edit', compact('form', 'table'));
        }

        /**
         *
         * Create a form to add content
         *
         * @param string $table
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws LoaderError
         * @throws NotFoundException
         * @throws RuntimeError
         * @throws SyntaxError
         *
         */
        public function create(string $table): Response
        {
            $form = $this->form(POST, 'admin', 'create', $table)->generate($table);

            return $this->view('@crud/create', compact('form', 'table'));
        }

        /**
         *
         * Update a record
         *
         * @param string $table
         * @param int $id
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function refresh(string $table, int $id): RedirectResponse
        {

            $this->init();

            $sql = $this->sql($table);

            $primary = $sql->key();

            $columns = collect();

            $values = collect($this->request()->request->all())->del(CSRF_TOKEN, '_method')->for('htmlentities')->all();

            foreach ($values as $k => $value) {
                if (different($k, $primary))
                    $columns->push("$k =" . $sql->connexion()->pdo()->quote($value));

            }

            $columns = $columns->join(', ');

            $query = "UPDATE  $table SET $columns WHERE $primary = $id";

            return $sql->connexion()->execute($query) ? $this->back($this->updated) : $this->back($this->no_updated, false);

        }

        /**
         *
         * Destroy a record
         *
         * @param string $table
         * @param int $id
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function destroy(string $table, int $id): RedirectResponse
        {
            $this->init();

            return $this->sql($table)->destroy($id) ? $this->back($this->deleted) : $this->back($this->no_deleted, false);
        }

        /**
         *
         * Crud home
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function home(): Response
        {
            $form = redirect_select($this->tables());
            return $this->view('@crud/home', compact('form'));
        }

        /**
         *
         * Generate a row in the table
         *
         * @param $key
         * @param $value
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function display($key, $value): string
        {
            $html = '<tr>';
            foreach ($value as $v)
                append($html, '<td>' . substr($v, 0, 50) . '</td>');

            $confirm = $this->config('crud', 'sure');
            $sure = "onclick=\"return confirm('$confirm');\"";

            append($html, '<td><a href="' . route('admin', 'edit', [$this->current, $value->id]) . '" class="' . $this->config('crud', 'edit_class') . '">' . $this->config('crud', 'edit_text') . '</a></td>');
            append($html, '<td><a href="' . route('admin', 'remove', [$this->current, $value->id]) . '" class="' . $this->config('crud', 'remove_class') . '" ' . $sure . '>  ' . $this->config('crud', 'remove_text') . '</a></td>');
            return $html . '</tr>';
        }

        /**
         *
         * @throws Kedavra
         */
        private function init()
        {

            $file = 'crud';

            $this->created = config($file, 'created');

            $this->no_created = config($file, 'no_created');

            $this->updated = config($file, 'updated');

            $this->no_updated = config($file, 'no_updated');

            $this->deleted = config($file, 'deleted');

            $this->no_truncated = config($file, 'no_truncated');

            $this->truncated = config($file, 'truncated');

        }


    }
}