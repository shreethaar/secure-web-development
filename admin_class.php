<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

/* Test if we can see errors
echo "Testing error display";
// Intentionally cause an error
$test = $undefinedVariable;
session_start();
ini_set('display_errors', 1);
Class Action {
    private $db;

    public function __construct() {
        ob_start();
        include 'dbconnection.php';
        $this->db = $dbh;
    }
 */
session_start();
include 'admin_class.php';
Class Action {
    private $db;

    public function __construct() {
        /*ob_start();
        try {
            include 'includes/dbconnection.php';
            $this->db = $dbh;
            echo "Database connection successful";
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }*/
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function __destruct() {
        ob_end_flush();
    }

    function login() {
        extract($_POST);
        $stmt = $this->db->prepare("SELECT *, CONCAT(fullname) as name FROM users WHERE email = :email AND password = :password");
        $stmt->execute([
            ':email' => $email,
            ':password' => md5($password)
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            foreach ($result[0] as $key => $value) {
                if ($key != 'password' && !is_numeric($key)) {
                    $_SESSION['login_'.$key] = $value;
                }
            }
            return 1;
        }
        return 3;
    }

    function logout() {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_user() {
        extract($_POST);
        $data = array();
        
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
                if ($k == 'password') {
                    $data[$k] = md5($v);
                } else {
                    $data[$k] = $v;
                }
            }
        }

        if (empty($id)) {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $stmt = $this->db->prepare("INSERT INTO users ($columns) VALUES ($values)");
            $save = $stmt->execute(array_values($data));
        } else {
            $set = array_map(function($key) {
                return "$key = ?";
            }, array_keys($data));
            $set = implode(',', $set);
            $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = ?");
            $values = array_values($data);
            $values[] = $id;
            $save = $stmt->execute($values);
        }

        return $save ? 1 : 0;
    }

    function update_user() {
        extract($_POST);
        $data = array();
        
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id','cpass','table')) && !is_numeric($k)) {
                if($k == 'password') {
                    $data[$k] = md5($v);
                } else {
                    $data[$k] = $v;
                }
            }
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email" . (!empty($id) ? " AND id != :id" : ""));
        $params = [':email' => $email];
        if (!empty($id)) {
            $params[':id'] = $id;
        }
        $stmt->execute($params);
        if($stmt->fetchColumn() > 0) {
            return 2;
        }

        if(empty($id)) {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $stmt = $this->db->prepare("INSERT INTO users ($columns) VALUES ($values)");
            $save = $stmt->execute(array_values($data));
        } else {
            $set = array_map(function($key) {
                return "$key = ?";
            }, array_keys($data));
            $set = implode(',', $set);
            $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = ?");
            $values = array_values($data);
            $values[] = $id;
            $save = $stmt->execute($values);
        }

        if($save) {
            foreach ($data as $key => $value) {
                if($key != 'password') {
                    $_SESSION['login_'.$key] = $value;
                }
            }
            return 1;
        }
        return 0;
    }

    function delete_user() {
        extract($_POST);
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $delete = $stmt->execute([$id]);
        return $delete ? 1 : 0;
    }

    function delete_message() {
        extract($_POST);
        $stmt = $this->db->prepare("DELETE FROM contact WHERE ID = ?");
        $delete = $stmt->execute([$id]);
        return $delete ? 1 : 0;
    }

    function save_categories() {
        extract($_POST);
        $data = array();
        
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id')) && !is_numeric($k)) {
                $data[$k] = $v;
            }
        }

        if(empty($id)) {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $stmt = $this->db->prepare("INSERT INTO categories ($columns) VALUES ($values)");
            $save = $stmt->execute(array_values($data));
        } else {
            $set = array_map(function($key) {
                return "$key = ?";
            }, array_keys($data));
            $set = implode(',', $set);
            $stmt = $this->db->prepare("UPDATE categories SET $set WHERE id = ?");
            $values = array_values($data);
            $values[] = $id;
            $save = $stmt->execute($values);
        }

        return $save ? 1 : 0;
    }

    function save_survey() {
        extract($_POST);
        $respondent_list = implode(',', $respondent);
        $data = array(
            'respondent' => $respondent_list
        );
        
        foreach($_POST as $k => $v) {
            if(!in_array($k, array('id', 'respondent')) && !is_numeric($k)) {
                $data[$k] = $v;
            }
        }

        if(empty($id)) {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $stmt = $this->db->prepare("INSERT INTO survey_set ($columns) VALUES ($values)");
            $save = $stmt->execute(array_values($data));
        } else {
            $set = array_map(function($key) {
                return "$key = ?";
            }, array_keys($data));
            $set = implode(',', $set);
            $stmt = $this->db->prepare("UPDATE survey_set SET $set WHERE id = ?");
            $values = array_values($data);
            $values[] = $id;
            $save = $stmt->execute($values);
        }

        return $save ? 1 : 0;
    }

    function save_question() {
        extract($_POST);
        $data = [
            'survey_id' => $sid,
            'question' => $question,
            'instruction' => $instruction,
            'type' => $type
        ];

        if($type != 'textfield_s') {
            $arr = array();
            foreach ($label as $k => $v) {
                do {
                    $k = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
                } while(isset($arr[$k]));
                $arr[$k] = $v;
            }
            $data['frm_option'] = json_encode($arr);
        } else {
            $data['frm_option'] = '';
        }

        if(empty($id)) {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $stmt = $this->db->prepare("INSERT INTO questions ($columns) VALUES ($values)");
            $save = $stmt->execute(array_values($data));
        } else {
            $set = array_map(function($key) {
                return "$key = ?";
            }, array_keys($data));
            $set = implode(',', $set);
            $stmt = $this->db->prepare("UPDATE questions SET $set WHERE id = ?");
            $values = array_values($data);
            $values[] = $id;
            $save = $stmt->execute($values);
        }

        return $save ? 1 : 0;
    }

    function delete_question() {
        extract($_POST);
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        $delete = $stmt->execute([$id]);
        return $delete ? 1 : 0;
    }

    function action_update_qsort() {
        extract($_POST);
        $success = true;
        
        $stmt = $this->db->prepare("UPDATE questions SET order_by = ? WHERE id = ?");
        $this->db->beginTransaction();
        
        try {
            foreach($qid as $key => $value) {
                $order = $key + 1;
                $stmt->execute([$order, $value]);
            }
            $this->db->commit();
            return 1;
        } catch(Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }

    function save_answer() {
        extract($_POST);
        $success = true;
        
        $stmt = $this->db->prepare("INSERT INTO answers (survey_id, question_id, user_id, answer) VALUES (?, ?, ?, ?)");
        $this->db->beginTransaction();
        
        try {
            foreach($qid as $k => $v) {
                $answer_value = ($type[$k] == 'check_opt') ? 
                    '[' . implode('],[', $answer[$k]) . ']' : 
                    $answer[$k];
                
                $stmt->execute([
                    $survey_id,
                    $qid[$k],
                    $_SESSION['login_id'],
                    $answer_value
                ]);
            }
            $this->db->commit();
            return 1;
        } catch(Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }

    function delete_comment() {
        extract($_POST);
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        $delete = $stmt->execute([$id]);
        return $delete ? 1 : 0;
    }

    function save_page_img() {
        extract($_POST);
        if(!empty($_FILES['img']['tmp_name'])) {
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $upload_path = 'assets/uploads/'. $fname;
            
            if(move_uploaded_file($_FILES['img']['tmp_name'], $upload_path)) {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $hostName = $_SERVER['HTTP_HOST'];
                $path = explode('/', $_SERVER['PHP_SELF']);
                $currentPath = '/'.$path[1];
                
                return json_encode([
                    'link' => $protocol.'://'.$hostName.$currentPath.'/admin/assets/uploads/'.$fname
                ]);
            }
        }
        return json_encode(['error' => 'Upload failed']);
    }
}
// Add this at the very end of the file
//$test = new Action();
?>
