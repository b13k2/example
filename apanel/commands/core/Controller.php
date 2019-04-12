<?php

namespace app\commands\core;

use yii\helpers\Console;


class Controller extends \yii\console\Controller
{
    // статистика работы скрипта
    protected $stats = [
        // число прочитанных строк
        'rowsRead'          => 0,
        // число обработанных строк
        'rowsProcessed'     => 0,

        // из них обновлено строк
        'updated'           => 0,
        // строк вставлено в базу
        'inserted'          => 0,

        // ошибок валидации
        'validationErrors'  => 0,
        // ошибок сохранения
        'saveErrors'        => 0,
    ];


    // выводим информационный текст
    public function text($text='')
    {
        $text = $this->ansiFormat($text, Console::FG_YELLOW) . "\n";
        $this->stdout($text);
    }

    // выводим ошибку
    public function error($text)
    {
        $text = $this->ansiFormat('ERROR: ', Console::FG_RED) . $text . "\n";
        $this->stderr($text);
    }

    // выводим ошибку и завершаем работу скрипта
    public function returnError($text)
    {
        $this->error($text . "\n");
        exit(1);
    }


    /*public function showError($text)
    {
        $text = $this->ansiFormat('ERROR:', Console::FG_RED) . ' ' . $text . "\n";
        $this->stderr($text);
        return 1;
    }

    public function showSuccess($text)
    {
        $text = $this->ansiFormat('OK:', Console::FG_GREEN) . ' ' . $text . "\n";
        $this->stdout($text);
        return 0;
    }*/


    protected function printErrors($model, $lineNum, $attrs)
    {
        foreach ($model->errors as $fieldName => $errorValue) {
            $text  = '';
            $text .= $this->ansiFormat(':: Ошибка на строке ' . $lineNum . ': ', Console::FG_RED) . $errorValue[0] . '; ';
            $text .= 'Поле: ' . $fieldName . ' = ' . $attrs[$fieldName] . ';' . "\n";
            $this->stdout($text);
        }
    }

    // выводим статистику работы скрипта на экран
    protected function showStats()
    {
        $text  = '';
        $text .= "\n";

        $text .= $this->ansiFormat('Строк прочитано: ',  Console::FG_YELLOW) . $this->stats['rowsRead'] . "\n";
        $text .= $this->ansiFormat('Строк обработано: ', Console::FG_YELLOW) . $this->stats['rowsProcessed'] . "\n";

        $text .= "\n";

        $text .= $this->ansiFormat('- из них обновлено: ', Console::FG_YELLOW) . $this->stats['updated'] . "\n";
        $text .= $this->ansiFormat('- вставлено в базу: ', Console::FG_YELLOW) . $this->stats['inserted'] . "\n";

        $text .= "\n";

        $text .= $this->ansiFormat('Ошибок валидации: ', Console::FG_YELLOW)
            . ($this->stats['validationErrors'] > 0
                ? $this->ansiFormat($this->stats['validationErrors'], Console::FG_RED)
                : $this->stats['validationErrors'])
            . "\n";

        $text .= $this->ansiFormat('Ошибок сохранения: ', Console::FG_YELLOW)
            . ($this->stats['saveErrors'] > 0
                ? $this->ansiFormat($this->stats['saveErrors'], Console::FG_RED)
                : $this->stats['saveErrors'])
            . "\n";

        $this->stdout($text);
    }
}

