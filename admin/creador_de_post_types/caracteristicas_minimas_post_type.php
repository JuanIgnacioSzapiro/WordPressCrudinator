<?php
class CaracteristicasMinimasPostType
{
    private $id_post_type;

    public function __construct(
        $id_post_type,
    ) {
        $this->set_id_post_type($id_post_type);
    }

    public function set_id_post_type($valor)
    {
        $this->id_post_type = $valor;
    }

    public function get_id_post_type()
    {
        return $this->id_post_type;
    }
    public function get_habilidades()
    {
        return [
            'edit_post' => 'edit_' . $this->get_id_post_type(),
            'read_post' => 'read_' . $this->get_id_post_type(),
            'delete_post' => 'delete_' . $this->get_id_post_type(),
            'edit_posts' => 'edit_multiples_' . $this->get_id_post_type(),
            'edit_others_posts' => 'edit_others_multiples_' . $this->get_id_post_type(),
            'publish_posts' => 'publish_multiples_' . $this->get_id_post_type(),
            'read_private_posts' => 'read_private_multiples_' . $this->get_id_post_type(),
            'delete_posts' => 'delete_multiples_' . $this->get_id_post_type(),
            'delete_private_posts' => 'delete_private_multiples_' . $this->get_id_post_type(),
            'delete_published_posts' => 'delete_published_multiples_' . $this->get_id_post_type(),
            'delete_others_posts' => 'delete_others_multiples_' . $this->get_id_post_type(),
            'edit_private_posts' => 'edit_private_multiples_' . $this->get_id_post_type(),
            'edit_published_posts' => 'edit_published_multiples_' . $this->get_id_post_type(),
            'create_posts' => 'create_multiples_' . $this->get_id_post_type(),
        ];
    }

}