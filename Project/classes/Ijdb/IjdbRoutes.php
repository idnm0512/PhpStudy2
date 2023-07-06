<?php
    namespace Ijdb;

    use \Hanbit\Authentication;
    use \Hanbit\DatabaseTable;
    use \Hanbit\Routes;
    use \Ijdb\Controllers\Joke;
    use \Ijdb\Controllers\Register;
    use \Ijdb\Controllers\Login;

    class IjdbRoutes implements Routes {
        private $jokesTable;
        private $authorsTable;
        private $authentication;

        public function __construct() {
            include __DIR__ . '/../../includes/DatabaseConnection.php';

            $this -> jokesTable = new DatabaseTable($pdo, 'joke', 'id');
            $this -> authorsTable = new DatabaseTable($pdo, 'author', 'id');
            $this -> authentication = new Authentication($this -> authorsTable, 'email', 'password');
        }

        public function getRoutes(): Array {
            $jokeController = new Joke($this -> jokesTable, $this -> authorsTable, $this -> authentication);
            $authorController = new Register($this -> authorsTable);
            $loginController = new Login($this -> authentication);

            $routes = [
                'joke/edit' => [
                    'POST' => [
                        'controller' => $jokeController,
                        'action' => 'saveEdit'
                    ],
                    'GET' => [
                        'controller' => $jokeController,
                        'action' => 'edit'
                    ],
                    'login' => true
                ],
                'joke/delete' => [
                    'POST' => [
                        'controller' => $jokeController,
                        'action' => 'delete'
                    ],
                    'login' => true
                ],
                'joke/list' => [
                    'GET' => [
                        'controller' => $jokeController,
                        'action' => 'list'
                    ]
                ],
                '' => [
                    'GET' => [
                        'controller' => $jokeController,
                        'action' => 'home'
                    ]
                ],
                'author/register' => [
                    'GET' => [
                        'controller' => $authorController,
                        'action' => 'registrationForm'
                    ],
                    'POST' => [
                        'controller' => $authorController,
                        'action' => 'registerUser'
                    ]
                ],
                'author/success' => [
                    'GET' => [
                        'controller' => $authorController,
                        'action' => 'success'
                    ]
                ],
                'login' => [
                    'GET' => [
                        'controller' => $loginController,
                        'action' => 'loginForm'
                    ],
                    'POST' => [
                        'controller' => $loginController,
                        'action' => 'processLogin'
                    ]
                ],
                'login/success' => [
                    'GET' => [
                        'controller' => $loginController,
                        'action' => 'success'
                    ],
                ],
                'login/error' => [
                    'GET' => [
                        'controller' => $loginController,
                        'action' => 'error'
                    ]
                ],
                'logout' => [
                    'GET' => [
                        'controller' => $loginController,
                        'action' => 'logout'
                    ]
                ]
            ];

            return $routes;
        }

        public function getAuthentication(): Authentication {
            return $this -> authentication;
        }
    }