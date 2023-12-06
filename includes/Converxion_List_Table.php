<?php


class Converxion_List_Table extends WP_List_Table {

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'converxion';

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        usort($data, array(&$this, 'usort_reorder'));

        $perPage = 5;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage-1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns() {
        $columns = array(
            'page_id'             => 'Page ID',
            'visits'              => 'Total Visits',
            'conversions'         => 'Conversions',
            'unique_conversions'  => 'Unique Conversions'
        );
        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function get_sortable_columns() {
        return array('visits' => array('visits', false));
    }

    protected function column_default($item, $column_name) {
        return $item[$column_name];
    }

    protected function usort_reorder($a, $b) {
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'visits';
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
        $result = strcmp($a[$orderby], $b[$orderby]);
        return ($order === 'asc') ? $result : -$result;
    }
}
