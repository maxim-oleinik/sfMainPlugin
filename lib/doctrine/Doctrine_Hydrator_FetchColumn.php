<?php

/**
 * Гидратор: возвращает массив значений первой колонки
 */
class Doctrine_Hydrator_FetchColumn extends Doctrine_Hydrator_Abstract
{
    public function hydrateResultSet($stmt)
    {
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
