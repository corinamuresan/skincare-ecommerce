<?php

class Product {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->pdo->prepare("
            SELECT p.*, b.nume as brand_nume, c.nume as categorie_nume 
            FROM produse p
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            ORDER BY p.nume ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, b.nume as brand_nume, c.nume as categorie_nume 
            FROM produse p
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getProductIngredients($produs_id) {
        $stmt = $this->pdo->prepare("
            SELECT i.* 
            FROM ingrediente i
            JOIN produs_ingrediente pi ON i.id = pi.ingredient_id
            WHERE pi.produs_id = ?
        ");
        $stmt->execute([$produs_id]);
        return $stmt->fetchAll();
    }

    public function getCompatibilityScore($produs_id, $profil) {
        $ingredients = $this->getProductIngredients($produs_id);
        $score = 100;
        $warnings = [];

        $sensibilitati_cunoscute = [];
        if(!empty($profil['sensibilitati'])) {
            $sensibilitati_cunoscute = explode(',', $profil['sensibilitati']);
            $sensibilitati_cunoscute = array_map('strtolower', $sensibilitati_cunoscute);
        }

        foreach($ingredients as $ingredient) {
            if($profil['predispus_acnee'] && $ingredient['rating_comedogenic'] >= 3) {
                $score -= 20;
                $warnings[] = $ingredient['nume'] . ' poate înfunda porii (comedogenic ' . $ingredient['rating_comedogenic'] . '/5)';
            }

            if($profil['sensibilitate'] && $ingredient['iritant'] == 1) {
                $score -= 20;
                $warnings[] = $ingredient['nume'] . ' poate irita pielea sensibilă';
            }

            if($ingredient['toxic'] == 1) {
                $score -= 30;
                $warnings[] = $ingredient['nume'] . ' este un ingredient controversat';
            }

            foreach($sensibilitati_cunoscute as $sensibilitate_cunoscuta) {
                if(strpos(strtolower($ingredient['nume']), trim($sensibilitate_cunoscuta)) !== false) {
                    $score -= 25;
                    $warnings[] = $ingredient['nume'] . ' este un ingredient la care ai indicat sensibilitate';
                    break;
                }
            }
        }

        if($score >= 80) {
            $status = 'compatibil';
        } elseif($score >= 50) {
            $status = 'atentie';
        } else {
            $status = 'evita';
        }

        return [
            'score' => max(0, $score),
            'status' => $status,
            'warnings' => $warnings
        ];
    }

    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categorii ORDER BY nume");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBrands() {
        $stmt = $this->pdo->prepare("SELECT * FROM branduri ORDER BY nume");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductsByCategory($categorie_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, b.nume as brand_nume, c.nume as categorie_nume 
            FROM produse p
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            WHERE p.categorie_id = ?
            ORDER BY p.nume ASC
        ");
        $stmt->execute([$categorie_id]);
        return $stmt->fetchAll();
    }

    public function getProductsByBrand($brand_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, b.nume as brand_nume, c.nume as categorie_nume 
            FROM produse p
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            WHERE p.brand_id = ?
            ORDER BY p.nume ASC
        ");
        $stmt->execute([$brand_id]);
        return $stmt->fetchAll();
    }

    public function getProductsByCategoryAndBrand($categorie_id, $brand_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, b.nume as brand_nume, c.nume as categorie_nume 
            FROM produse p
            JOIN branduri b ON p.brand_id = b.id
            JOIN categorii c ON p.categorie_id = c.id
            WHERE p.categorie_id = ? AND p.brand_id = ?
            ORDER BY p.nume ASC
        ");
        $stmt->execute([$categorie_id, $brand_id]);
        return $stmt->fetchAll();
    }

    public function getProductImage($produs_id) {
        $stmt = $this->pdo->prepare("SELECT url_imagine FROM imagini_produse WHERE produs_id = ? LIMIT 1");
        $stmt->execute([$produs_id]);
        $img = $stmt->fetch();
        return $img ? $img['url_imagine'] : null;
    }

    public function getRecommendations($profil) {
        $products = $this->getAllProducts();
        $recommendations = [];

        foreach($products as $p) {
            $compatibility = $this->getCompatibilityScore($p['id'], $profil);
            $p['compatibility'] = $compatibility;
            $recommendations[] = $p;
        }

        usort($recommendations, function($a, $b) {
            return $b['compatibility']['score'] - $a['compatibility']['score'];
        });

        return $recommendations;
    }
}