<?php

class Order {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

public function createOrder($user_id, $items, $total, $livrare = []) {
    $this->pdo->beginTransaction();
    
    try {
        $stmt = $this->pdo->prepare("INSERT INTO comenzi (utilizator_id, pret_total, status, prenume_livrare, nume_livrare, telefon_livrare, judet, localitate, adresa, cod_postal, metoda_livrare) VALUES (?, ?, 'in_asteptare', ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id, 
            $total,
            $livrare['prenume'] ?? '',
            $livrare['nume'] ?? '',
            $livrare['telefon'] ?? '',
            $livrare['judet'] ?? '',
            $livrare['localitate'] ?? '',
            $livrare['adresa'] ?? '',
            $livrare['cod_postal'] ?? '',
            $livrare['metoda_livrare'] ?? ''
        ]);
        $comanda_id = $this->pdo->lastInsertId();

        foreach($items as $item) {
            $stmt = $this->pdo->prepare("INSERT INTO produse_comanda (comanda_id, produs_id, cantitate, pret_la_moment) VALUES (?, ?, ?, ?)");
            $stmt->execute([$comanda_id, $item['produs_id'], $item['cantitate'], $item['pret']]);

            $stmt = $this->pdo->prepare("UPDATE produse SET stoc = stoc - ? WHERE id = ?");
            $stmt->execute([$item['cantitate'], $item['produs_id']]);
        }

        $this->pdo->commit();
        return $comanda_id;
    } catch(Exception $e) {
        $this->pdo->rollBack();
        return false;
    }
}

    public function updateOrderStatus($comanda_id, $status, $stripe_payment_id = null) {
        if($stripe_payment_id) {
            $stmt = $this->pdo->prepare("UPDATE comenzi SET status = ?, stripe_payment_id = ? WHERE id = ?");
            return $stmt->execute([$status, $stripe_payment_id, $comanda_id]);
        }
        $stmt = $this->pdo->prepare("UPDATE comenzi SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $comanda_id]);
    }

    public function createPayment($comanda_id, $stripe_payment_intent, $suma) {
        $stmt = $this->pdo->prepare("INSERT INTO plati (comanda_id, stripe_payment_intent, suma, status) VALUES (?, ?, ?, 'pending')");
        return $stmt->execute([$comanda_id, $stripe_payment_intent, $suma]);
    }

    public function updatePaymentStatus($stripe_payment_intent, $status) {
        $stmt = $this->pdo->prepare("UPDATE plati SET status = ? WHERE stripe_payment_intent = ?");
        return $stmt->execute([$status, $stripe_payment_intent]);
    }

    public function getOrdersByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM comenzi WHERE utilizator_id = ? ORDER BY data_creare DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getOrderItems($comanda_id) {
        $stmt = $this->pdo->prepare("
            SELECT pc.*, p.nume, b.nume as brand_nume
            FROM produse_comanda pc
            JOIN produse p ON pc.produs_id = p.id
            JOIN branduri b ON p.brand_id = b.id
            WHERE pc.comanda_id = ?
        ");
        $stmt->execute([$comanda_id]);
        return $stmt->fetchAll();
    }

    public function getAllOrders() {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.prenume, u.nume, u.email
            FROM comenzi c
            JOIN utilizatori u ON c.utilizator_id = u.id
            ORDER BY c.data_creare DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}