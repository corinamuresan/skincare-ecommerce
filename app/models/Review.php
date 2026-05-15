<?php

class Review {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function addReview($user_id, $produs_id, $rating, $comentariu) {
        $stmt = $this->pdo->prepare("SELECT id FROM reviewuri WHERE utilizator_id = ? AND produs_id = ?");
        $stmt->execute([$user_id, $produs_id]);
        $existing = $stmt->fetch();

        if($existing) {
            $stmt = $this->pdo->prepare("UPDATE reviewuri SET rating = ?, comentariu = ? WHERE utilizator_id = ? AND produs_id = ?");
            return $stmt->execute([$rating, $comentariu, $user_id, $produs_id]);
        }

        $stmt = $this->pdo->prepare("INSERT INTO reviewuri (utilizator_id, produs_id, rating, comentariu) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $produs_id, $rating, $comentariu]);
    }

    public function getProductReviews($produs_id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.prenume, u.nume
            FROM reviewuri r
            JOIN utilizatori u ON r.utilizator_id = u.id
            WHERE r.produs_id = ?
            ORDER BY r.data_creare DESC
        ");
        $stmt->execute([$produs_id]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($produs_id) {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviewuri WHERE produs_id = ?");
        $stmt->execute([$produs_id]);
        return $stmt->fetch();
    }

    public function getUserReview($user_id, $produs_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviewuri WHERE utilizator_id = ? AND produs_id = ?");
        $stmt->execute([$user_id, $produs_id]);
        return $stmt->fetch();
    }
}