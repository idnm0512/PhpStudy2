<?php
    namespace Ijdb;

    use \Hanbit\Authentication;
    use \Hanbit\DatabaseTable;
    use \Hanbit\Routes;
    use \Ijdb\Controllers\Category;
    use \Ijdb\Controllers\Joke;
    use \Ijdb\Controllers\Register;
    use \Ijdb\Controllers\Login;

    class IjdbRoutes implements Routes {
        private $jokesTable;
        private $authorsTable;
        private $categoriesTable;
        private $jokeCategoriesTable;
        private $authentication;

        public function __construct() {
            include __DIR__ . '/../../includes/DatabaseConnection.php';

            $this -> jokesTable = new DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this -> authorsTable,
                                                                                              &$this -> jokeCategoriesTable]);
            $this -> authorsTable = new DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this -> jokesTable]);
            $this -> categoriesTable = new DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category', [&$this -> jokesTable,
                                                                                                           &$this -> jokeCategoriesTable]);
            $this -> jokeCategoriesTable = new DatabaseTable($pdo, 'jokecategory', 'categoryId');
            $this -> authentication = new Authentication($this -> authorsTable, 'email', 'password');
        }

        public function getRoutes(): Array {
            $jokeController = new Joke($this -> jokesTable, $this -> authorsTable, $this -> categoriesTable, $this -> authentication);
            $authorController = new Register($this -> authorsTable);
            $loginController = new Login($this -> authentication);
            $categoryController = new Category($this -> categoriesTable);

            $routes = [
                'author/permissions' => [
                    'GET' => [
                        'controller' => $authorController,
                        'action' => 'permissions'
                    ],
                    'POST' => [
                        'controller' => $authorController,
                        'action' => 'savePermissions'
                    ],
                    'login' => true,
                    'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
                ],
                'author/list' => [
                    'GET' => [
                        'controller' => $authorController,
                        'action' => 'list'
                    ],
                    'login' => true,
                    'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
                ],
                'category/edit' => [
                    'POST' => [
                        'controller' => $categoryController,
                        'action' => 'saveEdit'
                    ],
                    'GET' => [
                        'controller' => $categoryController,
                        'action' => 'edit'
                    ],
                    'login' => true,
                    'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES
                ],
                'category/list' => [
                    'GET' => [
                        'controller' => $categoryController,
                        'action' => 'list'
                    ],
                    'login' => true,
                    'permissions' => \Ijdb\Entity\Author::LIST_CATEGORIES
                ],
                'category/delete' => [
                    'POST' => [
                        'controller' => $categoryController,
                        'action' => 'delete'
                    ],
                    'login' => true,
                    'permissions' => \Ijdb\Entity\Author::REMOVE_CATEGORIES
                ],
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

        public function checkPermission($permission): bool {
            $user = $this -> authentication -> getUser();

            if ($user && $user -> hasPermission($permission)) {
                return true;
            } else {
                return false;
            }
        }
    }