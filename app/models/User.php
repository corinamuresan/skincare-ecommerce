<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function register($prenume, $nume, $email, $telefon, $password) {
        $stmt = $this->pdo->prepare("SELECT id FROM utilizatori WHERE email = ?");
        $stmt->execute([$email]);
        
        if($stmt->fetch()) {
            return ['success' => false, 'message' => 'Acest email este deja înregistrat!'];
        }

        $parola_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("INSERT INTO utilizatori (prenume, nume, email, telefon, parola_hash) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$prenume, $nume, $email, $telefon, $parola_hash]);

        return ['success' => true, 'message' => 'Cont creat cu succes!'];
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilizatori WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if(!$user) {
            return ['success' => false, 'message' => 'Email sau parolă incorectă!'];
        }

        if(!password_verify($password, $user['parola_hash'])) {
            return ['success' => false, 'message' => 'Email sau parolă incorectă!'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['prenume'];
        $_SESSION['user_role'] = $user['rol'];

        return ['success' => true, 'message' => 'Autentificare reușită!'];
    }

    public function logout() {
        session_destroy();
    }

    public function getSkinProfile($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM profiluri_ten WHERE utilizator_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function saveSkinProfile($user_id, $tip_ten, $predispus_acnee, $sensibilitate, $sensibilitati = '') {
        $stmt = $this->pdo->prepare("SELECT id FROM profiluri_ten WHERE utilizator_id = ?");
        $stmt->execute([$user_id]);
        $existing = $stmt->fetch();

        if($existing) {
            $stmt = $this->pdo->prepare("UPDATE profiluri_ten SET tip_ten = ?, predispus_acnee = ?, sensibilitate = ?, nivel_hidratare = 'mediu', sensibilitati = ? WHERE utilizator_id = ?");
            return $stmt->execute([$tip_ten, $predispus_acnee, $sensibilitate, $sensibilitati, $user_id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO profiluri_ten (utilizator_id, tip_ten, predispus_acnee, sensibilitate, nivel_hidratare, sensibilitati) VALUES (?, ?, ?, ?, 'mediu', ?)");
            return $stmt->execute([$user_id, $tip_ten, $predispus_acnee, $sensibilitate, $sensibilitati]);
        }
    }

    public function generateSkinProfile($user_id) {
        $pdo = getConnection();
        
        $stmt = $pdo->prepare("SELECT r.text_raspuns, i.ordine FROM raspunsuri_utilizator_quiz rq 
            JOIN raspunsuri_quiz r ON rq.raspuns_id = r.id 
            JOIN intrebari_quiz i ON rq.intrebare_id = i.id 
            WHERE rq.utilizator_id = ? ORDER BY i.ordine");
        $stmt->execute([$user_id]);
        $raspunsuri = $stmt->fetchAll();

        $tip_ten = 'normal';
        $predispus_acnee = 0;
        $sensibilitate = 0;
        $sensibilitati = [];

        foreach($raspunsuri as $r) {
            $text = strtolower($r['text_raspuns']);
            $ordine = $r['ordine'];

            if($ordine == 5) {
                if(strpos($text, 'gras') !== false) $tip_ten = 'gras';
                elseif(strpos($text, 'uscat') !== false) $tip_ten = 'uscat';
                elseif(strpos($text, 'mixt') !== false) $tip_ten = 'mixt';
                elseif(strpos($text, 'sensibil') !== false) $tip_ten = 'sensibil';
                elseif(strpos($text, 'normal') !== false) $tip_ten = 'normal';
            }

            if($ordine == 2) {
                if(strpos($text, 'frecvent') !== false || strpos($text, 'ocazional') !== false) {
                    $predispus_acnee = 1;
                }
            }

            if($ordine == 7) {
                if(strpos($text, 'irită imediat') !== false || strpos($text, 'uneori') !== false) {
                    $sensibilitate = 1;
                }
            }

            if($ordine == 4 && strpos($text, 'sensibilitate') !== false) {
                $sensibilitate = 1;
            }

            if($ordine == 9 && strpos($text, 'nu am') === false) {
                $sensibilitati[] = $r['text_raspuns'];
            }
        }

        $sensibilitati_str = implode(',', $sensibilitati);

        return $this->saveSkinProfile($user_id, $tip_ten, $predispus_acnee, $sensibilitate, $sensibilitati_str);
    }
}