<?php
class UserHandler {
    public $db;
    public $username;
    public $password;
    public $token;
    public $userId;

    public function __construct($db) {
        $this->db = $db;
    }

    public function userInfo($token){
        if($this->checkToken($token)){
            
            $query = "SELECT * FROM users WHERE token='$token'";
            $resultUser = $this->db->query($query);

            if ($resultUser && $resultUser->num_rows > 0) {
                $user = $resultUser->fetch_assoc();
                $result = [
                    'status' => 'success',
                    'username' => $user['username'],
                    'name' => $user['first_name'],
                    'lastname' => $user['last_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                ]; 
            }            
        } else {
            $result = [
                'status' => 'error',
                'message' => 'User not found',
            ]; 
        }
        echo json_encode($result);
    }

    public function loginUser($username, $password) {
        $this->username = $username;
        $this->password = $password;
    
        if ($this->authenticateUser()) {
            $resultLogin = [
                'status' => 'success',
                'message' => 'user and pass success',
            ];  
            $resultLogin['token'] = $this->token;
        } else {
            $resultLogin = [
                'status' => 'error',
                'message' => 'user or pass error'
            ]; 
        }
        echo json_encode($resultLogin);
    }

    public function logoutUser() {
        // پیاده‌سازی عملیات خروج کاربر
    }

    

    public function updateProfile($data) {
        if($this->checkToken($data['token'])){
            $query = "UPDATE users SET " .
                (!empty($data['first_name']) ? "first_name = '{$this->db->real_escape_string($data['first_name'])}', " : "") .
                (!empty($data['last_name']) ? "last_name = '{$this->db->real_escape_string($data['last_name'])}', " : "") .
                (!empty($data['phone']) ? "phone = '{$this->db->real_escape_string($data['phone'])}', " : "") .
                (!empty($data['email']) ? "email = '{$this->db->real_escape_string($data['email'])}', " : "") .
                (!empty($data['password']) ? "password = '{$this->db->real_escape_string($data['password'])}', " : "");
            // حذف آخرین کاما و فاصله از انتهای کوئری
            $query = rtrim($query, ', ');
            $query .= " WHERE id = $this->userId";
            $result = $this->db->query($query);
            if($result){
                $resultUpdate = [
                    'status' => 'success',
                    'message' => 'success update data'
                ]; 
            }else{
                $resultUpdate = [
                    'status' => 'error',
                    'message' => 'error in update data'
                ]; 
            }
        }else{
            $resultUpdate = [
                'status' => 'error',
                'message' => 'error in token'
            ];
        }
        echo json_encode($resultUpdate);
    }
    
    public function generateToken($userId){
        // زمان فعلی
        $currentTimestamp = time();
        // زمان انقضای توکن (یک روز بعد)
        $expirationTimestamp = $currentTimestamp + (60 * 60 * 24);
        $expirationTime = $expirationTimestamp;
    
        $tokenPayload = [
            'user_id' => $userId,
            'expiration_time' => $expirationTime
        ];
    
        $token = json_encode($tokenPayload);
        $encodedToken = base64_encode($token);
        $this->token = $encodedToken; // ذخیره توکن در متغیر $this->token
        return $this->token; // بازگرداندن توکن
    }

    public function checkToken($token, $type = "") {
        $decodedToken = base64_decode($token);
        $tokenData = json_decode($decodedToken, true);
    
        $currentTimestamp = time();
        $expirationTime = $tokenData['expiration_time'];
    
        if ($currentTimestamp > $expirationTime) {
            return false;
        }else{
            $userId = $tokenData['user_id'];
            $query = "SELECT id FROM users WHERE token = '$token'";
            $result = $this->db->query($query);
            if ($result && $result->num_rows > 0) {
                $userIdFromDB = $result->fetch_assoc()['id'];
                if ($userId == $userIdFromDB) {
                    $this->userId = $userId;
                    return true;
                    if($type == "login"){
                        $result = [
                            'status' => 'success',
                            'message' => 'success update data'
                        ];
                        echo json_encode($result);
                    }
                }else{
                    return false;
                    if($type == "login"){
                        $result = [
                            'status' => 'error',
                            'message' => 'error in token'
                        ];
                        echo json_encode($result);
                    }
                }
            }
        }
    }
    

    public function updateToken($userId, $token) {
        $token = $this->db->real_escape_string($token);
        $query = "UPDATE users SET token = '$token' WHERE id = $userId";
        $result = $this->db->query($query);
        return $result !== false;
    }

    public function authenticateUser() {
        $username = $this->db->real_escape_string($this->username);
        $password = $this->db->real_escape_string($this->password);
        $query = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
        $result = $this->db->query($query);
    
        if ($result && $result->num_rows > 0) {
            $userId = $result->fetch_assoc()['id'];
            $token = $this->generateToken($userId);
            $this->updateToken($userId, $token);
            $query = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
        // استفاده از $userId برای تولید توکن
            return true;
        } else {
            return false;
        }
    }    
}
?>