<?php
class Database {
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;
    private $stmt;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            $this->showDbError($this->conn->connect_error);
        }
    }

    private function showDbError($msg) {
        http_response_code(200);
        $safe = htmlspecialchars($msg);
        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>YouFarm – DB Setup</title>
<style>
  body { margin:0; font-family:sans-serif; background:#0f172a; color:#f1f5f9;
         display:flex; align-items:center; justify-content:center; min-height:100vh; }
  .box { background:rgba(30,41,59,.85); border:1px solid rgba(255,255,255,.1);
         border-radius:16px; padding:2.5rem; max-width:500px; text-align:center; }
  h2   { color:#eab308; margin-bottom:.75rem; }
  p    { color:#94a3b8; line-height:1.75; }
  code { background:#1e293b; padding:2px 8px; border-radius:4px; color:#eab308; }
  .err { font-size:.8rem; color:#f87171; margin-top:1rem; }
</style>
</head>
<body>
<div class="box">
  <h2>&#9881;&#65039; Database Setup Required</h2>
  <p>YouFarm cannot connect to the database.<br>
     Open <code>app/config/config.php</code> in the InfinityFree File Manager<br>
     and fill in your MySQL credentials.</p>
  <p>Find them at:<br>
     <strong>InfinityFree Panel &rarr; MySQL Databases</strong></p>
  <p class="err">Error: {$safe}</p>
</div>
</body>
</html>
HTML;
        exit;
    }

    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
        return $this;
    }

    public function bind($params = []) {
        if (!empty($params)) {
            $types  = '';
            $values = [];
            foreach ($params as $param) {
                if (is_int($param))    $types .= 'i';
                elseif (is_float($param)) $types .= 'd';
                else                   $types .= 's';
                $values[] = $param;
            }
            $this->stmt->bind_param($types, ...$values);
        }
        return $this;
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function fetchAll() {
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchSingle() {
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_assoc();
    }
}
