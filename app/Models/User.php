<?php

namespace App\Models
{ 

	use Eywa\Database\Model\Model;
    use Eywa\Http\Request\Request;

    Class User extends Model
	{

		protected static string $table = 'auth';

		protected static string $by = 'id';

		protected static int $limit = 20;


        /**
         * @inheritDoc
         */
        public function before_validation(Request $request): bool
        {
            // TODO: Implement before_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function after_validation(Request $request): bool
        {
            // TODO: Implement after_validation() method.
        }

        /**
         * @inheritDoc
         */
        public function before_save(Request $request): bool
        {
            // TODO: Implement before_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_save(Request $request): bool
        {
            // TODO: Implement after_save() method.
        }

        /**
         * @inheritDoc
         */
        public function after_commit(Request $request): bool
        {
            // TODO: Implement after_commit() method.
        }

        /**
         * @inheritDoc
         */
        public function after_rollback(Request $request): bool
        {
            // TODO: Implement after_rollback() method.
        }

        /**
         * @inheritDoc
         */
        public function before_update(Request $request): bool
        {
            // TODO: Implement before_update() method.
        }

        /**
         * @inheritDoc
         */
        public function after_update(Request $request): bool
        {
            // TODO: Implement after_update() method.
        }

        /**
         * @inheritDoc
         */
        public function before_create(Request $request): bool
        {
            // TODO: Implement before_create() method.
        }

        /**
         * @inheritDoc
         */
        public function after_create(Request $request): bool
        {
            // TODO: Implement after_create() method.
        }

        /**
         * @inheritDoc
         */
        public function before_destroy(Request $request): bool
        {
            // TODO: Implement before_destroy() method.
        }

        /**
         * @inheritDoc
         */
        public function after_destroy(Request $request): bool
        {
            // TODO: Implement after_destroy() method.
        }
    }

}
