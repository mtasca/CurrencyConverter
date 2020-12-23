<?php
declare(strict_types=1);

namespace CurrencyConverter\Infraestructure\MySql;


use CurrencyConverter\Application\Exception\EntityNotFoundException;
use CurrencyConverter\Domain\Foundation\Repository\CurrencyRepositoryInterface;
use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Domain\Model\Currency\CurrencyId;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use PDO;

class CurrencyRepository extends BaseRepository implements CurrencyRepositoryInterface
{

    const CURRENCY_TABLE = 'currencies';

    public function getCurrencyByIsoCode(CurrencyIsoCode $currency_iso_code) : Currency
    {
        $q = $this->db_read->createQueryBuilder()
            ->select('*')
            ->from(self::CURRENCY_TABLE, 'c')
            ->where('c.iso_code = :iso_code')
            ->setParameter('iso_code', $currency_iso_code->getValue());

        $data = $q->execute()->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data[0])) {
            throw new EntityNotFoundException('Currency Not Found');
        }

        $data = $data[0];
        return new Currency(
            new CurrencyId(filter_var( $data['id'], FILTER_VALIDATE_INT)),
            $data['name'],
            $data['description'],
            $currency_iso_code,
            filter_var( $data['iso_number'], FILTER_VALIDATE_INT)
        );
    }
}
