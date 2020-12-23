<?php
declare(strict_types=1);

namespace CurrencyConverter\Infraestructure\MySql;


use Doctrine\DBAL\Connection;

class BaseRepository
{
    protected $db_read;
    protected $db_write;

    public function __construct(
        Connection $db_read,
        Connection $db_write
    ) {
        $this->db_read  = $db_read;
        $this->db_write = $db_write;
    }

    protected function bulkDelete(
        $table,
        $identifier,
        $limit
    ) {
        $deleted_items = 0;

        $q = $this->db_write->createQueryBuilder();
        $q->delete($table);

        foreach ($identifier as $key => $value) {
            $q->andWhere(sprintf('%s=:%s', $key, $key))
                ->setParameter($key, $value);
        }

        do {
            $count = $this->db_write->executeUpdate(
                sprintf('%s LIMIT %s', $q->getSQL(), $limit),
                $q->getParameters()
            );
            $deleted_items += $count;
        } while ($count > 0);

        return $deleted_items;
    }
}