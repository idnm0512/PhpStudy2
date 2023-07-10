<?php
    namespace Ijdb\Controllers;

    use \Hanbit\DatabaseTable;
    use \Hanbit\Authentication;

    class Joke {
        private $jokesTable;
        private $authorsTable;
        private $categoriesTable;
        private $authentication;

        public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable,
                                        Authentication $authentication) {
            $this -> jokesTable = $jokesTable;
            $this -> authorsTable = $authorsTable;
            $this -> categoriesTable = $categoriesTable;
            $this -> authentication = $authentication;
        }

        public function home() {
            $title = '인터넷 유머 세상';

            return [
                'template' => 'home.html.php',
                'title' => $title
            ];
        }

        public function list() {
            if (isset($_GET['category'])) {
                $category = $this -> categoriesTable -> findById($_GET['category']);

                $jokes = $category -> getJokes();
            } else {
                $jokes = $this -> jokesTable -> findAll();
            }

            $title = '유머 글 목록';

            $totalJokes = $this -> jokesTable -> total();

            $author = $this -> authentication -> getUser();

            return [
                'template' => 'jokes.html.php',
                'title' => $title,
                'variables' => [
                    'totalJokes' => $totalJokes,
                    'jokes' => $jokes,
                    'user' => $author,
                    'categories' => $this -> categoriesTable -> findAll()
                ]
            ];
        }

        public function saveEdit() {
            $author = $this -> authentication -> getUser();

            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();

            $jokeEntity = $author -> addJoke($joke);

            $jokeEntity -> clearCategories();

            foreach ($_POST['category'] as $categoryId) {
                $jokeEntity -> addCategory($categoryId);
            }

            header('location: /joke/list');
        }

        public function edit() {
            $author = $this -> authentication -> getUser();
            $categories = $this -> categoriesTable -> findAll();

            $title = '유머 글 등록';

            if (isset($_GET['id'])) {
                $joke = $this -> jokesTable -> findById($_GET['id']);

                $title = '유머 글 수정';
            }

            return [
                'template' => 'editjoke.html.php',
                'title' => $title,
                'variables' => [
                    'joke' => $joke ?? null,
                    'user' => $author,
                    'categories' => $categories
                ]
            ];
        }

        public function delete() {
            $author = $this -> authentication -> getUser();

            $joke = $this -> jokesTable -> findById($_POST['id']);

            if ($joke -> authorId != $author -> id && !$author -> hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)) {
                return;
            }

            $this -> jokesTable -> delete($_POST['id']);

            header('location: /joke/list');
        }
    }