<?php
    function query($pdo, $sql, $parameters = []) {
        $query = $pdo -> prepare($sql);

        $query -> execute($parameters);

        return $query;
    }

    function totalJokes($pdo) {
        $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');

        $row = $query -> fetch();

        return $row[0];
    }

    function getJoke($pdo, $id) {
        $parameters = [':id' => $id];

        $query = query($pdo, 'SELECT * FROM `joke` WHERE `id` = :id', $parameters);

        return $query -> fetch();
    }

    function allJokes($pdo) {
        $query = 'SELECT `joke`.`id`, `joketext`, `name`, `email`
                    FROM `joke`
              INNER JOIN `author`
                      ON `authorId` = `author`.`id`';

        $jokes = query($pdo, $query);

        return $jokes -> fetchAll();
    }

    function insertJoke($pdo, $joketext, $authorId) {
        $query = 'INSERT INTO `joke` (`joketext`, `jokedate`, `authorId`)
                  VALUES (:joketext, CURDATE(), :authorId)';

        $parameters = [
            ':joketext' => $joketext,
            ':authorId' => $authorId,
        ];

        query($pdo, $query, $parameters);
    }

    function updateJoke($pdo, $jokeId, $joketext, $authorId) {
        $query = 'UPDATE `joke`
                     SET `joketext` = :joketext, `authorId` = :authorId
                   WHERE `id` = :id';

        $parameters = [
            ':joketext' => $joketext,
            ':authorId' => $authorId,
            ':id' => $jokeId,
        ];

        query($pdo, $query, $parameters);
    }

    function deleteJoke($pdo, $id) {
        $query = 'DELETE FROM `joke` WHERE `id` = :id';

        $parameters = [':id' => $id];

        query($pdo, $query, $parameters);
    }