<?php
    if(!isset($_SESSION['visits'])) {
        $_SESSION['visits'] = 0;
    }

    $_SESSION['visits'] = $_SESSION['visits'] + 1;

    setcookie('visits', $visits, time() + 3600 * 24 * 365);

    if ($_SESSION['visits'] > 1) {
        echo $_SESSION['visits'] . '번째 방문하셨습니다.';
    } else {
        echo '웹사이트에 오신 걸 환영합니다!';
    }