<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcLicencesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_licences');
    }
}