<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_081434_init extends Migration
{
    public function up()
    {
        $file = Yii::getAlias('@app') . '/data/jdoc.sql';
        $sql = file_get_contents($file);
        $this->execute($sql);
    }

    public function down()
    {
        echo "m160201_081434_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
