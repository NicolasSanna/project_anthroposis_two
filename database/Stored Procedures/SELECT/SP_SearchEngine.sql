-- Procédure stockée de type MySQL

DELIMITER //
DROP PROCEDURE IF EXISTS SP_SearchEngine //
CREATE PROCEDURE SP_SearchEngine(v_search VARCHAR(255))
BEGIN

    -- Création de la variable locale à la procéédure stockée v_srch et assignation de sa valeur au paramètre entré en concaténant les caractères % % correspondant à la recherche avant et après la chaîne de caractères entourées en SQL.
    DECLARE v_srch VARCHAR(255) DEFAULT CONCAT('%', v_search, '%');

    SELECT u.pseudo, a.title, a.description, a.slug, DATE_FORMAT(a.created_at, '%d/%m/%Y à %H:%i') AS created_at_fr
    FROM article AS a
    INNER JOIN user AS u ON a.user_id = u.id
    -- Précision de l'ordonnancement et du regroupement des caractères de manière explicite (COLLATE)
    WHERE a.content COLLATE utf8mb4_unicode_ci LIKE v_srch
    OR a.description COLLATE utf8mb4_unicode_ci LIKE v_srch;

END //