<?php
    $roll1 = rand(1, 6);
    $roll2 = rand(1, 6);

    echo '<p>주사위를 굴려서 나온 숫자 : ' . $roll1 . ' 그리고 ' . $roll2 . '</p>';

    if ($roll1 == 6 && $roll2 == 6) {
        echo '<p>이겼다!</p>';
    } else {
        echo '아쉽지만 \'꽝\' 이네요. 다음 기회를 노려보세요!';
    }

    echo '<p>게임에 참여해주셔서 감사합니다.</p>';