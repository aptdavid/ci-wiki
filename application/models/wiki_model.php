<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * A simple wiki.
 *
 * TODO everything
 */

class Wiki_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();

    $this->table_name = 'wiki_pages';
    $this->table_name_revisions = 'wiki_revisions';

		$this->load->helper('diff');
    $this->check_tables();
  }

	function page_exists( $page_name )
	{
		$this->db->select('id');
	  $res = $this->db->get_where( $this->table_name, array('title' => $page_name ) );
	  return $res->num_rows() > 0;
	}

  function get_page( $page_name )
  {
    $res = $this->db->get_where( $this->table_name, array('title' => $page_name ) );
    return $res->num_rows() ? $res->row() : null;
  }

  function add_page( $title, $body, $user )
  {
    $this->db->set('title', $title );
    $this->db->set('body', $body );
    $this->db->set('user', $user );
    $this->db->set('created_on', 'NOW()', false );
    $this->db->insert( $this->table_name );
    // store the full original text
    $this->add_revision( $this->db->insert_id(), $title, $body, $user );
  }

  function update_page( $id, $title, $body, $user )
  {
    $old_page = $this->db->get_where( $this->table_name, array('id' => $id ));
    $old_page = $old_page->row();

    $this->db->where('id', $id );
    $this->db->set('title', $title );
    $this->db->set('body', $body );
    $this->db->update( $this->table_name );

    $this->add_revision( $id, $title, diff($old_page->body, $body), $user );
  }

  function add_revision( $page_id, $title, $body, $user )
  {
    // no revisions for the sandbox page
    if( strtolower($title) == 'sandbox' ) {
      return;
    }
    $this->db->set('page_id', $page_id );
    $this->db->set('title', $title );
    $this->db->set('body', $body );
    $this->db->set('user', $user );
    $this->db->set('created_on', 'NOW()', false );
    $this->db->insert( $this->table_name_revisions );
  }

  function get_revisions( $page_id )
  {
    $this->db->order_by('created_on', 'DESC');
    return $this->db->get_where( $this->table_name_revisions, array('page_id' => $page_id ));
  }

  function get_revision( $id )
  {
    return $this->db->get_where( $this->table_name_revisions, array('id' => $id ));
  }

	function recent_changes()
	{
		$this->db->order_by('created_on', 'DESC' );
		$this->db->limit( 20 );
		return $this->db->get( $this->table_name_revisions );
	}

	function site_index()
	{
		$this->db->select('id, title, created_on, user');
		$this->db->order_by('title');
		return $this->db->get( $this->table_name );
	}

	function search( $query )
	{
		$items = explode( " ", $query );
		foreach( $items as $term ) {
			$this->db->or_like('body', $term );
			$this->db->or_like('title', $term );
		}
		$this->db->order_by('title');
		return $this->db->get( $this->table_name );
	}


  function check_tables()
  {
    $tables = array(
      $this->table_name => "CREATE TABLE `wiki_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `body` text,
  `created_on` timestamp NULL DEFAULT NULL,
  `user` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8",
      $this->table_name_revisions => "CREATE TABLE `wiki_revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `body` text,
  `user` varchar(128) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    foreach( $tables as $tbl => $sql ) {
      if( !$this->db->table_exists($tbl)) {
        $this->db->query( $sql );
      }
    }
  }

}