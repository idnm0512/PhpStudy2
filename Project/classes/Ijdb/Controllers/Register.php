<?php
    namespace Ijdb\Controllers;

    use \Hanbit\DatabaseTable;

    class Register {
        private $authorsTable;

        public function __construct(DatabaseTable $authorsTable) {
            $this -> authorsTable = $authorsTable;
        }

        public function list() {
            $authors = $this -> authorsTable -> findAll();

            return [
                'template' => 'authorlist.html.php',
                'title' => '사용자 목록',
                'variables' => [
                    'authors' => $authors
                ]
            ];
        }

        public function permissions() {
            $author = $this -> authorsTable -> findById($_GET['id']);

            $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
            $constants = $reflected -> getConstants();

            return [
                'template' => 'permissions.html.php',
                'title' => '권한 수정',
                'variables' => [
                    'author' => $author,
                    'permissions' => $constants
                ]
            ];
        }

        public function savePermissions() {
            $author = [
                'id' => $_GET['id'],
                'permissions' => array_sum($_POST['permissions'] ?? [])
            ];

            $this -> authorsTable -> save($author);

            header('location: /author/list');
        }

        public function registrationForm() {
            return [
                'template' => 'register.html.php',
                'title' => '사용자 등록'
            ];
        }

        public function success() {
            return [
                'template' => 'registersuccess.html.php',
                'title' => '등록 성공'
            ];
        }

        public function registerUser() {
            $author = $_POST['author'];

            $valid = true;
            $errors = [];

            if (empty($author['email'])) {
                $valid = false;
                $errors[] = '이메일을 입력해야 합니다.';
            } else if (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
                $valid = false;
                $errors[] = '유효하지 않은 이메일 주소입니다.';
            } else {
                $author['email'] = strtolower($author['email']);

                if (count($this -> authorsTable -> find('email', $author['email'])) > 0) {
                    $valid = false;
                    $errors[] = '이미 가입된 이메일 주소입니다.';
                }
            }

            if (empty($author['name'])) {
                $valid = false;
                $errors[] = '이름을 입력해야 합니다.';
            }

            if (empty($author['password'])) {
                $valid = false;
                $errors[] = '패스워드를 입력해야 합니다.';
            }

            if ($valid == true) {
                $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

                $this -> authorsTable -> save($author);

                header('location: /author/success');
            } else {
                return [
                    'template' => 'register.html.php',
                    'title' => '사용자 등록',
                    'variables' => [
                        'errors' => $errors,
                        'author' => $author
                    ]
                ];
            }
        }
    }