<?php
    $testVariable = 1 + 1; // 2 할당
    $testVariable = 1 - 1; // 0 할당
    $testVariable = 2 * 2; // 4 할당
    $testVariable = 2 / 2; // 1 할당
    $testVariable = '여어 ' . '안녕!'; // '여어 안녕!' 할당

    $var1 = 'PHP'; // 'PHP' 할당
    $var2 = 5; // 5 할당
    $var3 = $var2 + 1; // 6 할당
    $var4 = $var1; // 'PHP' 할당

    echo $var1 . ' 규칙!'; // 'PHP 규칙!' 출력
    echo '$var1 규칙!'; // '$var1 규칙!' 출력
    echo "$var1 규칙!"; // 'PHP 규칙!' 출력