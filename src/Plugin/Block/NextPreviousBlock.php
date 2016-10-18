<?php

/**
 * @file
 * Contains \Drupal\rino_prev_next\Plugin\Block\NextPreviousBlock.
 */

namespace Drupal\rino_prev_next\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\Core\Language;
use Drupal\Core\Entity\Query;
use Drupal\node\Entity;
use Drupal\node\Entity\Node;


/**
 * Provides a 'Next Previous' block.
 *
 * @Block(
 *   id = "next_previous_block",
 *   admin_label = @Translation("Next Previous Block"),
 *   category = @Translation("Blocks")
 * )
 */
class NextPreviousBlock extends BlockBase {

  

  /**
   * Lookup the previous node, i.e. youngest node which is still older than the node
   * currently being viewed.
   *
   * @param  string $created_time A unix time stamp
   * @return string               an html link to the previous node
   */
  private function generatePrevious($created_time) {
    return $this->generateNextPrevious('prev', $created_time);
  }

  /**
   * Lookup the next node, i.e. oldest node which is still younger than the node
   * currently being viewed.
   *
   * @param  string $created_time A unix time stamp
   * @return string               an html link to the next node
   */
  private function generateNext($created_time) {
    return $this->generateNextPrevious('next', $created_time);
  }

  /**
   * Lookup the next or previous node
   *
   * @param  string $direction    either 'next' or 'previous'
   * @param  string $created_time a Unix time stamp
   * @return string               an html link to the next or previous node
   */
  private function generateNextPrevious($direction = 'next', $created_time) {

    if ($direction === 'prev') {
      $comparison_opperator = '<';
      $sort = 'DESC';
    }
    elseif($direction === 'next'){
      $comparison_opperator = '>';
      $sort = 'ASC';
    }

    $langcode =  \Drupal::languageManager()->getCurrentLanguage()->getId();

    //Lookup 1 node younger (or older) than the current node

    $query = \Drupal::entityQuery('node');
    $next = $query->condition('created', $created_time, $comparison_opperator)
      ->condition('langcode', $langcode)
      ->condition('type', 'article')
      ->condition('status', 1)
      ->sort('created', $sort)
      ->range(0, 1)
      ->execute();

    //If this is not the youngest (or oldest) node
    if (!empty($next) && is_array($next)) {
      $next = array_values($next);
      $next = $next[0];

      //Find the alias of the next node
      $next_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $next);

      $node = Node::load($next);
      $title = $node->get('title')->value;
      

      //Build the URL of the next node
      $next_url = Url::fromUri('internal:' . $next_url);
      

    }else{
      $next_url = "";
    }


    return array ($next_url, $title);

  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    //Get the created time of the current node
    $node = \Drupal::request()->attributes->get('node');
<<<<<<< HEAD
    if($node) {
      $created_time = $node->getCreatedTime();
      $prev = array_values($this->generatePrevious($created_time));
      $next = array_values($this->generateNext($created_time));
      $element = array(
          '#theme' => 'rino_next',
          '#next' => $next[0],
          '#next_title' => $next[1],
          '#prev' => $prev[0],
          '#prev_title' => $prev[1],
          '#cache' => [
              'max-age' => 0,
          ],
      );
      return $element;
=======
    
    if($node){
    $created_time = $node->getCreatedTime();
    $prev= array_values($this->generatePrevious($created_time));
    $next= array_values($this->generateNext($created_time));
    $element = array(
        '#theme' => 'rino_next',
        '#next' => $next[0],
        '#next_title' => $next[1],
        '#prev' => $prev[0],
        '#prev_title' => $prev[1],
        '#cache' => [
            'max-age' => 0,
        ],
    );
    return $element;
>>>>>>> c1867ecb59c4f310085b5befdae7d9cab4b04164
    }
  }
}
