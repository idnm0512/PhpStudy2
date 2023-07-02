<?php
    $roll = rand(1, 6);

    if ($roll == 1) {
        $korean = '하나';
    } else if ($roll == 2) {
        $korean = '둘';
    } // else if ..

    echo '<p>주사위를 굴려서 나온 숫자 : ' . $korean . '</p>';

    if ($roll == 6) {
        echo '<p>이겼다!</p>';
    } else {
        echo '아쉽지만 \'꽝\' 이네요. 다음 기회를 노려보세요!';
    }

    echo '<p>게임에 참여해주셔서 감사합니다.</p>';