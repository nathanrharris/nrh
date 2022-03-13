<?php

namespace Drupal\nrh_migration\Commands;

use Drush\Commands\DrushCommands;

class NrhMigrationCommands extends DrushCommands {
  
 /**
  * @command nrh_migration:project
  * @aliases nrh-pr
  */
  public function migrate_projects() {

    \Drupal\Core\Database\Database::setActiveConnection('wp');

    $db = \Drupal\Core\Database\Database::getConnection();

    $query = $db->select('wp_posts', 'w');

    $query->fields('w', ['ID', 'post_title', 'menu_order']);
    
    $query->condition('post_type', 'project');

    $results = $query->execute()->fetchAll();

    $projects = [];

    foreach ($results as $r) {
      $this->writeLn('Loading: ' . $r->post_title); 
      
      $projects[$r->ID] = [
        'type' => 'project',
        'title' => $r->post_title,
        'field_weight' => (96 - $r->menu_order),
      ];

      $query_tax = $db->select('wp_term_relationships', 'r');
      
      $query_tax->join('wp_terms', 't', 't.term_id = r.term_taxonomy_id');

      $query_tax->fields('r', ['term_taxonomy_id']);
      
      $query_tax->fields('t', ['name']);

      $query_tax->condition('object_id', $r->ID);

      $query_tax->orderBy('t.term_order');

      $terms = $query_tax->execute()->fetchAll();

      $tax_fields = [
        'field_tasks' => 'task',
        'field_client' => 'client',
        'field_project_description' => 'project_description',
        'field_role' => 'role',
        'field_team' => 'team',
        'field_technology' => 'technology',
      ];

      foreach ($tax_fields as $tf => $tf_vocab) {
        $terms_list = [];

        foreach ($terms as $t) {
          if ($tid = $this->findTerm($t->name, $tf_vocab)) {
            $terms_list[] = $tid; 
          }
        }


        $projects[$r->ID][$tf] = array_unique($terms_list);
      }

      $query_meta = $db->select('wp_postmeta', 'm');

      $query_meta->fields('m', ['meta_key', 'meta_value']);

      $query_meta->condition('post_id', $r->ID);

      $meta_data = $query_meta->execute()->fetchAll();

      $meta = [
        'field_short_title' => 'short_title',
        'field_direct' => 'direct',
        'field_featured' => 'featured',
        'field_url' => 'url',
        'field_contractor' => 'contractor_/_employer',
      ];

      $screenshot_ids = [];

      foreach ($meta_data as $md) {
        foreach ($meta as $f => $q) {
          if ($q == $md->meta_key) {
            $projects[$r->ID][$f] = $md->meta_value;
          }

          if (preg_match('/^screenshot/', $md->meta_key)) {
            if (($md->meta_value) && !in_array($md->meta_value, $screenshot_ids)) {
              $screenshot_ids[] = $md->meta_value;
            }
          }
        }
      }

      foreach ($meta_data as $md) {
        if (in_array($md->meta_key, ['color_1', 'color_2', 'color_3'])) {
          $projects[$r->ID]['field_color'][] = [
            'color' => $md->meta_value,
            'opacity' => 1,
          ];
        }
      }

      foreach ($screenshot_ids as $id) {
        $query_ss = $db->select('wp_posts', 'w');

        $query_ss->fields('w', ['guid']);

        $query_ss->condition('ID', $id);

        $screenshot_data = $query_ss->execute()->fetchAll();

        $path = str_replace('http://www.nathanrharris.com', '/app', $screenshot_data[0]->guid);
        $path = str_replace('http://portfolio.local', '/app', $path);

        $filename = preg_replace('/.*\//', '', $path);

        $this->writeLn($path); 
        $this->writeLn($filename); 
        $this->writeLn('----------');

        $data = file_get_contents($path);

        $file = file_save_data($data, "public://$filename", 1);

        $projects[$r->ID]['field_screenshot'][] = $file->id();
      }
    }

    \Drupal\Core\Database\Database::setActiveConnection();

    foreach ($projects as $p) {

      $node = \Drupal\node\Entity\Node::create($p);

      $node->save();
      
      $this->writeLn('Writing: ' . $p['title']); 
    }
  }
 
 /**
  * @command nrh_migration:pr-clear
  * @aliases nrh-pr-clear
  */
  public function clear_projects() {
    $db = \Drupal\Core\Database\Database::getConnection();
  
    $query = $db->select('node_field_data', 'n');

    $query->fields('n', ['nid', 'title']);
    
    $query->condition('type', 'project');

    $results = $query->execute()->fetchAll();

    foreach ($results as $r) {
      $this->writeLn('Deleting: ' . $r->title);

      $node = \Drupal\node\Entity\Node::load($r->nid);

      $node->delete();
    }
  }
 
 /**
  * @command nrh_migration:taxonomy
  * @aliases nrh-tax
  */
  public function migrate_taxonomy() {

    \Drupal\Core\Database\Database::setActiveConnection('wp');

    $db = \Drupal\Core\Database\Database::getConnection();

    $query = $db->select('wp_term_taxonomy', 'w');
    
    $query->join('wp_terms', 't', 'w.term_id = t.term_id');

    $query->fields('w', ['taxonomy', 'term_id', 'term_taxonomy_id']);
    $query->fields('t', ['name', 'term_order']);

    $query->orderBy('t.term_order');

    $results = $query->execute()->fetchAll();
 
    $tax = [];

    foreach ($results as $r) {
      $tax[$r->taxonomy][$r->term_id] = $r->name;
    }
    
    \Drupal\Core\Database\Database::setActiveConnection();

    foreach ($tax as $vocab => $terms) {
      $vocabulary = \Drupal\taxonomy\Entity\Vocabulary::create([
        'vid' => $vocab,
        'description' => '',
        'name' => ucwords(str_replace('_', ' ', $vocab)),
      ])->save();

      $this->writeLn('Creating vocabulary: ' . ucwords(str_replace('_', ' ', $vocab)));

      foreach ($terms as $t) {
        $term = \Drupal\taxonomy\Entity\Term::create([
          'name' => $t,
          'vid' => $vocab,
        ])->save();
      
        $this->writeLn('Creating term: ' . $t);
      }
    }
    
  }

 /**
  * @command nrh_migration:taxonomy-clean
  * @aliases nrh-tax-clean
  */
  public function clean_taxonomy() {
    $this->writeLn('Cleaning taxonomy');

    $vocabs = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();

    foreach ($vocabs as $v) {
    
      $this->writeLn('Deleting: ' . $v->get('name'));
    
      $v->delete();
    }
        
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple();

    foreach ($terms as $t) {
    
      $this->writeLn('Deleting: ' . $t->get('name')->value);
    
      $t->delete();
    }
  }

 /**
  * @command nrh_migration:files-clean
  * @aliases nrh-files-clean
  */
  public function clean_files() {
    $this->writeLn('Cleaning files');

    $files = \Drupal\file\Entity\File::loadMultiple();

    foreach ($files as $f) {
    
      $this->writeLn('Deleting: ' . $f->get('uri')->first()->value);
    
      $f->delete();
    }
  }


  public function findTerm($name, $vocab) {
    $term = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $name]);

    $t = array_shift($term);

    if ($t->vid->first()->target_id == $vocab) {
      return $t->id();
    }

    return NULL;
  }
}
