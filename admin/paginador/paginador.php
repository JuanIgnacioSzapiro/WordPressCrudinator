<?php

class Paginador
{
    private $title_of_the_page, $content;
    public function get_title_of_the_page()
    {
        return $this->title_of_the_page;
    }
    public function set_title_of_the_page($valor)
    {
        $this->title_of_the_page = $valor;
    }
    public function get_content()
    {
        return $this->content;
    }
    public function set_content($valor)
    {
        $this->content = $valor;
    }

    public function __construct($title_of_the_page, $content)
    {
        $this->set_title_of_the_page($title_of_the_page);
        $this->set_content($content);
    }

    function create_page($title_of_the_page, $content, $parent_id = NULL)
    {
        $page_id = wp_insert_post(
            array(
                'comment_status' => 'close',
                'ping_status' => 'close',
                'post_author' => 1,
                'post_title' => $title_of_the_page,
                'post_name' => strtolower(str_replace(' ', '_', trim($title_of_the_page))),
                'post_status' => 'publish',
                'post_content' => $content,
                'post_type' => 'page',
                'post_parent' => $parent_id //'id_of_the_parent_page_if_it_available'
            )
        );
        return $page_id;
    }

    function new_get_page_by_title($page_title, $output = OBJECT, $post_type = 'page')
    {
        $args = array(
            'title' => $page_title,
            'post_type' => $post_type,
            'post_status' => get_post_stati(),
            'posts_per_page' => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
            'orderby' => 'post_date ID',
            'order' => 'ASC',
        );
        $query = new WP_Query($args);
        $pages = $query->posts;

        if (empty($pages)) {
            return null;
        }

        return get_post($pages[0], $output);
    }
}
