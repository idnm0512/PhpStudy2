<?php
    namespace Hanbit;

    use \Hanbit\Authentication;

    interface Routes {
        public function getRoutes(): Array;
        public function getAuthentication(): Authentication;
        public function checkPermission($permission): bool;
    }