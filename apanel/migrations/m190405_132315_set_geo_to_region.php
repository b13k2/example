<?php

use yii\db\Migration;

/**
 * Class m190405_132315_set_geo_to_region
 */
class m190405_132315_set_geo_to_region extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('{{%region}}', [
        'latitude' => 56.7888000,
        'longitude' => 60.6034000,
        ], ['id' => 132]);

        $this->update('{{%region}}', [
        'latitude' => 54.5169000,
        'longitude' => 36.2832000,
        ], ['id' => 133]);

        $this->update('{{%region}}', [
        'latitude' => 55.3434000,
        'longitude' => 86.1001000,
        ], ['id' => 134]);

        $this->update('{{%region}}', [
        'latitude' => 56.2560000,
        'longitude' => 93.5372000,
        ], ['id' => 135]);

        $this->update('{{%region}}', [
        'latitude' => 55.7595000,
        'longitude' => 37.6254000,
        ], ['id' => 136]);

        $this->update('{{%region}}', [
        'latitude' => 55.0300000,
        'longitude' => 82.9300000,
        ], ['id' => 137]);

        $this->update('{{%region}}', [
        'latitude' => 54.9800000,
        'longitude' => 73.3900000,
        ], ['id' => 138]);

        $this->update('{{%region}}', [
        'latitude' => 59.9289000,
        'longitude' => 30.3277000,
        ], ['id' => 139]);

        $this->update('{{%region}}', [
        'latitude' => 54.7800000,
        'longitude' => 32.0200000,
        ], ['id' => 140]);

        $this->update('{{%region}}', [
        'latitude' => 56.8500000,
        'longitude' => 35.9100000,
        ], ['id' => 141]);

        $this->update('{{%region}}', [
        'latitude' => 56.4938000,
        'longitude' => 84.9582000,
        ], ['id' => 142]);

        $this->update('{{%region}}', [
        'latitude' => 57.1400000,
        'longitude' => 65.5300000,
        ], ['id' => 143]);

        $this->update('{{%region}}', [
        'latitude' => 55.1595000,
        'longitude' => 61.3982000,
        ], ['id' => 144]);

        $this->update('{{%region}}', [
        'latitude' => 57.6259000,
        'longitude' => 39.8620000,
        ], ['id' => 145]);

        $this->update('{{%region}}', [
        'latitude' => 53.3476000,
        'longitude' => 83.7779000,
        ], ['id' => 233]);

        $this->update('{{%region}}', [
        'latitude' => 56.9952000,
        'longitude' => 40.9740000,
        ], ['id' => 234]);

        $this->update('{{%region}}', [
        'latitude' => 56.2877000,
        'longitude' => 43.8576000,
        ], ['id' => 235]);

        $this->update('{{%region}}', [
        'latitude' => 58.5228000,
        'longitude' => 31.2600000,
        ], ['id' => 236]);

        $this->update('{{%region}}', [
        'latitude' => 57.8188000,
        'longitude' => 28.3427000,
        ], ['id' => 237]);

        $this->update('{{%region}}', [
        'latitude' => 45.0448400,
        'longitude' => 38.9760300,
        ], ['id' => 1236]);

        $this->update('{{%region}}', [
        'latitude' => 51.1801000,
        'longitude' => 71.4459800,
        ], ['id' => 3230]);

        $this->update('{{%region}}', [
        'latitude' => 38.5357500,
        'longitude' => 68.7790500,
        ], ['id' => 3457]);

        $this->update('{{%region}}', [
        'latitude' => 42.8700000,
        'longitude' => 74.5900000,
        ], ['id' => 3485]);

        $this->update('{{%region}}', [
        'latitude' => 54.6251000,
        'longitude' => 39.7486000,
        ], ['id' => 34013]);

        $this->update('{{%region}}', [
        'latitude' => 61.7827000,
        'longitude' => 34.3533000,
        ], ['id' => 34014]);

        $this->update('{{%region}}', [
        'latitude' => 56.1344000,
        'longitude' => 40.4032000,
        ], ['id' => 375309]);

        $this->update('{{%region}}', [
        'latitude' => 59.2121000,
        'longitude' => 39.8898000,
        ], ['id' => 504900]);

        $this->update('{{%region}}', [
        'latitude' => 55.4411000,
        'longitude' => 65.3472000,
        ], ['id' => 584142]);

        $this->update('{{%region}}', [
        'latitude' => 61.0083000,
        'longitude' => 69.0301000,
        ], ['id' => 584143]);

        $this->update('{{%region}}', [
        'latitude' => 66.5321000,
        'longitude' => 66.6099000,
        ], ['id' => 588981]);

        $this->update('{{%region}}', [
        'latitude' => 58.0022000,
        'longitude' => 56.2405000,
        ], ['id' => 907963]);

        $this->update('{{%region}}', [
        'latitude' => 57.7682000,
        'longitude' => 40.9305000,
        ], ['id' => 2929242]);

        $this->update('{{%region}}', [
        'latitude' => 53.2500000,
        'longitude' => 50.1600000,
        ], ['id' => 4389479]);

        $this->update('{{%region}}', [
        'latitude' => 53.7200000,
        'longitude' => 91.4600000,
        ], ['id' => 6752639]);

        $this->update('{{%region}}', [
        'latitude' => 52.2900000,
        'longitude' => 104.3000000,
        ], ['id' => 7271989]);

        $this->update('{{%region}}', [
        'latitude' => 53.1923000,
        'longitude' => 45.0225000,
        ], ['id' => 7840276]);

        $this->update('{{%region}}', [
        'latitude' => 58.6000000,
        'longitude' => 49.6500000,
        ], ['id' => 8860028]);

        $this->update('{{%region}}', [
        'latitude' => 54.1900000,
        'longitude' => 37.6100000,
        ], ['id' => 9588076]);

        $this->update('{{%region}}', [
        'latitude' => 51.9574000,
        'longitude' => 85.9608000,
        ], ['id' => 11748599]);

        $this->update('{{%region}}', [
        'latitude' => 54.7128000,
        'longitude' => 20.5055000,
        ], ['id' => 16625093]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190405_132315_set_geo_to_region cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190405_132315_set_geo_to_region cannot be reverted.\n";

        return false;
    }
    */
}
