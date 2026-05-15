<?php

class Wishlist {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getOrCreateWishlist($user_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM wishlisturi WHERE utilizator_id = ?");
        $stmt->execute([$user_id]);
        $wishlist = $stmt->fetch();

        if(!$wishlist) {
            $stmt = $this->pdo->prepare("INSERT INTO wishlisturi (utilizator_id) VALUES (?)");
            $stmt->execute([$user_id]);
            return $this->pdo->lastInsertId();
        }

        return $wishlist['id'];
    }

    public function addToWishlist($user_id, $produs_id) {
        $wishlist_id = $this->getOrCreateWishlist($user_id);

        $stmt = $this->pdo->prepare("SELECT id FROM produse_wishlist WHERE wishlist_id = ? AND produs_id = ?");
        $stmt->execute([$wishlist_id, $produs_id]);
        $existing = $stmt->fetch();

        if($existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO produse_wishlist (wishlist_id, produs_id) VALUES (?, ?)");
        return $stmt->execute([$wishlist_id, $produs_id]);
    }

    public function removeFromWishlist($user_id, $produs_id) {
        $wishlist_id = $this->getOrCreateWishlist($user_id);
        $stmt = $this->pdo->prepare("DELETE FROM produse_wishlist WHERE wishlist_id = ? AND produs_id = ?");
        return $stmt->execute([$wishlist_id, $produs_id]);
    }

    public function getWishlistItems($user_id) {
        $wishlist_id = $this->getOrCreateWishlist($user_id);

        $stmt = $this->pdo->prepare("
            SELECT pw.*, p.nume, p.pret, p.descriere, b.nume as brand_nume, c.nume as categorie_nume
            FROM produse_wishlist pw
            JOIN produse p ON pw.produs_id = p.id
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            WHERE pw.wishlist_id = ?
        ");
        $stmt->execute([$wishlist_id]);
        return $stmt->fetchAll();
    }

    public function isInWishlist($user_id, $produs_id) {
        $wishlist_id = $this->getOrCreateWishlist($user_id);
        $stmt = $this->pdo->prepare("SELECT id FROM produse_wishlist WHERE wishlist_id = ? AND produs_id = ?");
        $stmt->execute([$wishlist_id, $produs_id]);
        return $stmt->fetch() ? true : false;
    }
}