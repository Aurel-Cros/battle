<?php
class DbAccess
{

    /**
     * @var ?PDO
     * @access private
     * @static
     */
    private static $instance = null;


    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {

            $dotenv = Dotenv\Dotenv::createImmutable(FilesManager::rootDirectory());
            $dotenv->load();

            $db_info = array(
                "db_host" => $_ENV['DB_HOST'],
                "db_port" => "3306",
                "db_user" => $_ENV['DB_USER'],
                "db_pass" => $_ENV['DB_PASSWORD'],
                "db_name" => $_ENV['DB_NAME'],
                "db_charset" => "UTF-8"
            );

            try {
                self::$instance = new PDO("mysql:host=" . $db_info['db_host'] . ';port=' . $db_info['db_port'] . ';dbname=' . $db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');
            } catch (PDOException $error) {
                echo $error->getMessage();
            }
        }

        return self::$instance;
    }
}
