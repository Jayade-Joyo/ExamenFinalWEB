<?php
require_once __DIR__ . '/../models/EtablissementFinancier.php';
require_once __DIR__ . '/../helpers/Utils.php';

class EtablissementController {
    public static function getAll() {
        $etablissements = EtablissementFinancier::getAll();
        Flight::json($etablissements);
    }

    public static function getById($id) {
        $etablissement = EtablissementFinancier::getById($id);
        Flight::json($etablissement);
    }

    public static function create() {
        $data = Flight::request()->data;
        if (empty($data->nom_etablissement)) {
            Flight::json(['message' => 'Le nom de l\'établissement est requis'], 400);
            return;
        }
        $id = EtablissementFinancier::create($data);
        Flight::json(['message' => 'Établissement ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        if (empty($data->nom_etablissement)) {
            Flight::json(['message' => 'Le nom de l\'établissement est requis'], 400);
            return;
        }
        EtablissementFinancier::update($id, $data);
        Flight::json(['message' => 'Établissement modifié']);
    }

    public static function delete($id) {
        EtablissementFinancier::delete($id);
        Flight::json(['message' => 'Établissement supprimé']);
    }
}