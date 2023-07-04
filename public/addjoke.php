<?php
    if (isset($_POST['joketext'])) {
        try {
            include __DIR__ . '/../includes/DatabaseConnection.php';
            include __DIR__ . '/../includes/DatabaseFunctions.php';

            insertJoke($pdo, $_POST['joketext'], 1);

            header('location: jokes.php');
        } catch (PDOException $e) {
            $title = '오류가 발생했습니다.';

            $output = '데이터베이스 서버에 접속할 수 없습니다.'
                . '<br>내용: ' . $e -> getMessage()
                . '<br>위치: ' . $e -> getFile()
                . '<br>라인: ' . $e -> getLine();
        }
    } else {
        $title = '유머 글 등록';

        ob_start();

        include __DIR__ . '/../templates/addjoke.html.php';

        $output = ob_get_clean();
    }

    include __DIR__ . '/../templates/layout.html.php';