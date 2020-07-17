<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\View;
use App\Models\Logged;

class FrontController
{
    private $view;
    private $start;
    private $lastInsertId;

    public function __construct()
    {
        $this->view = new View();
        $this->start = microtime(true);
    }

    public function show()
    {
        $message = '';
        if (!empty($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $html = $this->view->render('layout.php', array('message' => $message));

        (new Response($html))->send();
    }

    public function formProcess()
    {
        $date = !empty($_POST['date']) ? $_POST['date'] : '';
        $validateResult = $this->validate($date);

        if (is_array($validateResult)) {
            $this->lastInsertId = (new Logged())->create($validateResult);
            $_SESSION['message'] = 'Ваш запрос успешно обработан';
            header('Location: /');
            exit;
        }

        $html = $this->view->render(
            'layout.php',
            array(
                'date' => $date,
                'error' => $validateResult
            )
        );
        (new Response($html))->send();
    }

    public function ajaxProcess()
    {
        $date = !empty($_POST['date']) ? $_POST['date'] : '';
        $validateResult = $this->validate($date);


        if (is_array($validateResult)) {
            $this->lastInsertId = (new Logged())->create($validateResult);
            $response = array(
                'success' => true,
                'message' => 'Ваш запрос успешно обработан'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => $validateResult
            );
        }

        (new Response($response))->json();
    }

    private function getIp()
    {
        $forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $forwarded;
    }

    private function validate($date)
    {
        if (!$date) {
            return 'Введите дату';
        }

        $dateSplit = explode('-', $date);
        $dateSplit = array_map('trim', $dateSplit);
        if (count($dateSplit) !== 2) {
            return 'Введите две даты разделенные символом "-"';
        }

        list($dateFrom, $dateTo) = $dateSplit;

        $dateFrom = $this->getDateFromString($dateFrom);
        $dateTo = $this->getDateFromString($dateTo);

        if (!$dateFrom || !$dateTo) {
            $str = 'Даты должны быть в формате Y/m/d или m.d.Y<br>';
            $str .= 'd - День месяца, 2 цифры с ведущим нулём<br>';
            $str .= 'm - Порядковый номер месяца с ведущим нулём<br>';
            $str .= 'Y - Порядковый номер года, 4 цифры';

            return $str;
        }

        if ($dateFrom >= $dateTo) {
            return 'Дата справа должна быть больше';
        }

        return array(
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'dateDiff' => date_diff(new \DateTime($dateTo . ' 00:00:00'), new \DateTime($dateFrom . ' 00:00:00'))->days,
            'timeProcess' => '',
            'ip' => $this->getIp(),
        );
    }

    private function getDateFromString($dateString)
    {
        $testArray = explode('/', $dateString);
        if (count($testArray) === 3) {
            list($year, $month, $day) = $testArray;
            if (checkdate($month, $day, $year)) {
                return $year . '-' . $month . '-' . $day;
            }
        }

        $testArray = explode('.', $dateString);
        if (count($testArray) === 3) {
            list($month, $day, $year) = $testArray;
            if (checkdate($month, $day, $year)) {
                return $year . '-' . $month . '-' . $day;
            }
        }

        return false;
    }

    public function __destruct()
    {
        $time = microtime(true) - $this->start;
        (new Logged())->updateTimeProcess($this->lastInsertId, $time);
    }
}
