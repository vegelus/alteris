<?php

namespace AppBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository {

    public function getAllCategory() {

        $sql = "SELECT category.*, location_text, location_children FROM category INNER JOIN location ON(location_category = id) ORDER BY category_matrix ASC";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    /**
     * Zwraca order dzieci dla wskazanego rodzica
     * @param type $parentId
     * @return type
     */
    public function getMaxSeq($parentId) {

        $sql = "SELECT max(category_order)
                FROM `category`
                WHERE `category_parent` =$parentId";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * funkcja służy do zmieniania kolejności wyświetlania w obrębie jednego katalogu
     * @param type $category_id
     * @param type $seq na której pozycji ma się znaleźć plik
     */
//    public function setCategoryOrder($category_id, $seq) {
//        $sql = "CALL MENU_ORDER($category_id, $seq);";
//
//        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
//        $stmt->execute();
//
//        return true;
//    }

    /**
     * funckacja przenosi plik lub katalg do nowej lokacji 
     * @param type $category plik lub katalog do przeniesienia
     * @param type $new_folder nowe miejsce 
     */
//    public function moveCategory($category, $new_folder) {
//        $sql = "CALL MENU_MOVE($category, $new_folder);";
//
//        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
//        $stmt->execute();
//
//        return true;
//    }

}