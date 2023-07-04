<?php
    try {
        include __DIR__ . '/../includes/autoload.php';

        $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

        $entryPoint = new \Hanbit\EntryPoint($route, new \Ijdb\IjdbRoutes(), $_SERVER['REQUEST_METHOD']);

        $entryPoint -> run();
    } catch (PDOException $e) {
        $title = '오류가 발생했습니다.';

        $output = '데이터베이스 서버에 접속할 수 없습니다.'
            . '<br>내용: ' . $e -> getMessage()
            . '<br>위치: ' . $e -> getFile()
            . '<br>라인: ' . $e -> getLine();
        
        include __DIR__ . '/../templates/layout.html.php';
    }