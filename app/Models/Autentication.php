<?php

namespace App\Models {

    use Eywa\Collection\Collect;
    use Eywa\Database\Model\Model;
    use stdClass;

    final class Autentication extends Model
    {
        /**
         * @inheritDoc
         */
        protected static string $table = 'users';

        /**
         * @inheritDoc
         */
        protected static int $limit = 24;

        /**
         * @inheritDoc
         */
        protected static string $slug = 'users';

        /**
         * @inheritDoc
         */
        protected static string $prefix = '';

        /**
         * @inheritDoc
         */
        protected static string $html_before_pagination_content = '<div>';

        /**
         * @inheritDoc
         */
        protected static string $html_after_pagination_content = '</nav>';

        /**
         * @inheritDoc
         */
        protected static string $html_before_records_content = '<div>';

        /**
         * @inheritDoc
         */
        protected static string $html_after_records_content = '</div>';


        /**
         * @inheritDoc
         */
        protected static function beforeDestroy(stdClass $record): bool
        {
            return $record->id !== '30';
        }

        /**
         * @inheritDoc
         */
        protected static function afterDestroy(stdClass $record): void
        {
            d($record);
        }

        /**
         * @inheritDoc
         */
        protected static function beforeUpdate(stdClass $record): bool
        {
            return $record->id !== '30';
        }

        /**
         * @inheritDoc
         */
        protected static function afterUpdate(stdClass $record): void
        {
            d($record);
        }

        /**
         * @inheritDoc
         */
        protected static function beforeCreate(Collect $origin, array &$modify): bool
        {
            $modify = ['username' => '<script >alert("a");</script>','email' => '<?= phpinfo();?>','phone' => 'a'];
            return  $modify !== $origin->all();
        }

        /**
         * @inheritDoc
         */
        protected static function afterCreate(array $data): void
        {
        }
    }
}
