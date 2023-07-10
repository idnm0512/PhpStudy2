<?php
    namespace Ijdb\Controllers;

    use \Hanbit\DatabaseTable;

    class Category {
        private $categoriesTable;

        public function __construct(DatabaseTable $categoriesTable) {
            $this -> categoriesTable = $categoriesTable;
        }

        public function list() {
            $categories = $this -> categoriesTable -> findAll();

            $title = '카테고리 목록';

            return [
                'template' => 'categories.html.php',
                'title' => $title,
                'variables' => [
                    'categories' => $categories
                ]
            ];
        }

        public function edit() {
            $title = '카테고리 등록';

            if (isset($_GET['id'])) {
                $category = $this -> categoriesTable -> findById($_GET['id']);

                $title = '카테고리 수정';
            }

            return [
                'template' => 'editcategory.html.php',
                'title' => $title,
                'variables' => [
                    'category' => $category ?? null
                ]
            ];
        }
        
        public function saveEdit() {
            $category = $_POST['category'];

            $this -> categoriesTable -> save($category);

            header('location: /category/list');
        }

        public function delete() {
            $this -> categoriesTable -> delete($_POST['id']);

            header('location: /category/list');
        }
    }