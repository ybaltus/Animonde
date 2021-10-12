<?php
namespace Vendor\database;

class Database
{
    /**
     * Database user
     */
    private string $dbUser;
    /**
     * Database password
     */
    private string $dbPassword;
    /**
     * Database name
     */
    private string $dbName;
    /**
     * Database host
     */
    private string $dbHost;
    /**
     * Database port
     */
    private int $dbPort;
    /**
     * Database object (PDO)
     */
    private ?\PDO $db;

    public function __construct(){
        // Init parameters
        $this->setDbName(getenv('DB_NAME'));
        $this->setDbHost(getenv('DB_HOST'));
        $this->setDbPort(getenv('DB_PORT'));
        $this->setDbUser(getenv('DB_USER'));
        $this->setDbPassword(getenv('DB_PASSWORD'));
        // Create the datatabse if not exist
        $this->createDatabase();
        // Connection to the database
        $this->connection();
    }

    public function __destruct() {
        // Destroy the pdo
        $this->db = null;
    }

    /**
     * Database creation (mariadb ou mysql)
     */
    private function createDatabase(): void {
        try{
            $this->db = new \PDO("mysql:host={$this->getDbHost()}:{$this->getDbPort()}",
                "{$this->getDbUser()}", "{$this->getDbPassword()}");

            $statement = "CREATE DATABASE IF NOT EXISTS `$this->dbName`";

            $prepare = $this->db?->prepare($statement);
            $prepare?->execute();
        }catch (\PDOException $e){
            die("Database error: ".$e->getMessage());
        }
    }

    /**
     * Database connection (mariadb ou mysql)
     */
    private function connection (): void {
        $this->db = new \PDO("mysql:host={$this->getDbHost()}:{$this->getDbPort()};dbname={$this->getDbName()}",
            "{$this->getDbUser()}", "{$this->getDbPassword()}");
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function setDbUser(string $dbUser): void
    {
        $this->dbUser = $dbUser;
    }

    public function getDbPassword(): string
    {
        return $this->dbPassword;
    }

    public function setDbPassword(string $dbPassword): void
    {
        $this->dbPassword = $dbPassword;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function setDbHost(string $dbHost): void
    {
        $this->dbHost = $dbHost;
    }

    public function getDbPort(): int
    {
        return $this->dbPort;
    }

    public function setDbPort(int $dbPort): void
    {
        $this->dbPort = $dbPort;
    }

    public function getDb(): ?\PDO
    {
        return $this->db;
    }
}