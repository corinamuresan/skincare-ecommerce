<?php

class Cart {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getOrCreateCart($user_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM cosuri WHERE utilizator_id = ?");
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch();

        if(!$cart) {
            $stmt = $this->pdo->prepare("INSERT INTO cosuri (utilizator_id) VALUES (?)");
            $stmt->execute([$user_id]);
            return $this->pdo->lastInsertId();
        }

        return $cart['id'];
    }

    public function addToCart($user_id, $produs_id, $cantitate = 1) {
        $cos_id = $this->getOrCreateCart($user_id);

        $stmt = $this->pdo->prepare("SELECT id, cantitate FROM produse_cos WHERE cos_id = ? AND produs_id = ?");
        $stmt->execute([$cos_id, $produs_id]);
        $existing = $stmt->fetch();

        if($existing) {
            $stmt = $this->pdo->prepare("UPDATE produse_cos SET cantitate = cantitate + ? WHERE id = ?");
            $stmt->execute([$cantitate, $existing['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO produse_cos (cos_id, produs_id, cantitate) VALUES (?, ?, ?)");
            $stmt->execute([$cos_id, $produs_id, $cantitate]);
        }

        return true;
    }

    public function getCartItems($user_id) {
        $cos_id = $this->getOrCreateCart($user_id);

        $stmt = $this->pdo->prepare("
            SELECT pc.*, p.nume, p.pret, p.stoc, b.nume as brand_nume
            FROM produse_cos pc
            JOIN produse p ON pc.produs_id = p.id
            JOIN branduri b ON p.brand_id = b.id
            WHERE pc.cos_id = ?
        ");
        $stmt->execute([$cos_id]);
        return $stmt->fetchAll();
    }

    public function removeFromCart($user_id, $produs_cos_id) {
        $cos_id = $this->getOrCreateCart($user_id);
        $stmt = $this->pdo->prepare("DELETE FROM produse_cos WHERE id = ? AND cos_id = ?");
        return $stmt->execute([$produs_cos_id, $cos_id]);
    }

    public function updateQuantity($user_id, $produs_cos_id, $cantitate) {
        $cos_id = $this->getOrCreateCart($user_id);
        if($cantitate <= 0) {
            return $this->removeFromCart($user_id, $produs_cos_id);
        }
        $stmt = $this->pdo->prepare("UPDATE produse_cos SET cantitate = ? WHERE id = ? AND cos_id = ?");
        return $stmt->execute([$cantitate, $produs_cos_id, $cos_id]);
    }

    public function getCartTotal($user_id) {
        $items = $this->getCartItems($user_id);
        $total = 0;
        foreach($items as $item) {
            $total += $item['pret'] * $item['cantitate'];
        }
        return $total;
    }

    public function clearCart($user_id) {
        $cos_id = $this->getOrCreateCart($user_id);
        $stmt = $this->pdo->prepare("DELETE FROM produse_cos WHERE cos_id = ?");
        return $stmt->execute([$cos_id]);
    }
}