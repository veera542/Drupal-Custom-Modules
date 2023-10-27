<?php

namespace Drupal\starwars_paragraph\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;

/**
 * creating a paragraph type.
 */
class ParagraphController extends ControllerBase {
    public function createParagraph() {
        // Add a Paragraph type.
        $paragraphs_type_faq = ParagraphsType::create([
            'id' => 'faq',
            'label' => 'Quick Links',
        ]);
        $paragraphs_type_faq->save();

        $paragraph = Paragraph::create([
            'type' => 'faq',
            'field_title' => array(
                "value"  =>  'How do I purchase movie tickets?'
            ),
            'field_description' => array(
                "value"  =>  'To purchase movie tickets, simply go to our website, select the movie you want to watch, choose your preferred showtime, and follow the prompts to complete your purchase. You can also buy tickets at the theaterTo purchase movie tickets, simply go to our website, select the movie you want to watch, choose your preferred showtime, and follow the prompts to complete your purchase. You can also buy tickets at the theater box office',
                "format" => "full_html"
            ),
            'field_link' => array(
                "value"  =>  'www.google.com',
                "type" => "url"
            ),
        ]);
        $build = [
            '#markup' => $this->t('Paragraph type saved'),
        ];
        return $build;

    }
}