<?php

namespace Drupal\starwars_paragraph\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\ParagraphsType;
use \Drupal\field\Entity\FieldConfig;
use \Drupal\field\Entity\FieldStorageConfig;
use Drupal\Core\Entity\Entity\EntityFormDisplay;

/**
 * Controller for managing paragraph type and fields.
 */
class ParagraphController extends ControllerBase {

    /**
     * Create a custom paragraph type and its fields.
     */
    public function createParagraph() {
        $paragraphTypeId = 'faq';

        if (!$this->paragraphTypeExists($paragraphTypeId)) {
            $this->createCustomParagraphType($paragraphTypeId);
        }

        $build = [
            '#markup' => $this->t('Paragraph type saved or already exists.'),
        ];
        return $build;
    }

    /**
     * Check if the paragraph type already exists.
     */
    private function paragraphTypeExists($paragraphTypeId) {
        $paragraphType = ParagraphsType::load($paragraphTypeId);
        return !empty($paragraphType);
    }

    /**
     * Create a custom paragraph type with fields.
     */
    public function createCustomParagraphType($paragraphTypeId) {
        $paragraphsType = ParagraphsType::create([
            'id' => $paragraphTypeId,
            'label' => 'FAQ',
        ]);
        $paragraphsType->save();

        $this->createField('field_title', 'Title', 'The question for the FAQ.', true, 'string', 255, $paragraphTypeId);
        $this->createField('field_body', 'Short Text', 'The answer for the FAQ.', false, 'text_long', 0, $paragraphTypeId);
        $this->createField('field_link', 'Link', 'The link for the FAQ.', false, 'link', 0, $paragraphTypeId);

        $this->setFormDisplay($paragraphTypeId);

        return true;
    }

    /**
     * Create a field.
     */
    private function createField($fieldName, $label, $description, $required, $fieldType, $maxLength, $bundle) {
        $fieldStorage = FieldStorageConfig::create([
            'field_name' => $fieldName,
            'entity_type' => 'paragraph',
            'type' => $fieldType,
            'settings' => [
                'max_length' => $maxLength,
            ],
        ]);
        $fieldStorage->save();

        $fieldConfig = FieldConfig::create([
            'field_storage' => $fieldStorage,
            'bundle' => $bundle,
            'label' => $label,
            'description' => $description,
            'required' => $required,
        ]);
        $fieldConfig->save();
    }

    /**
     * Set form display for the paragraph type.
     */
    private function setFormDisplay($bundle) {
        $formDisplay = EntityFormDisplay::load("paragraph.$bundle.default");

        if (!$formDisplay) {
            $formDisplay = EntityFormDisplay::create([
                'targetEntityType' => 'paragraph',
                'bundle' => $bundle,
                'mode' => 'default',
                'status' => true,
            ]);
        }

        $formDisplay->setComponent('field_title', ['type' => 'string_textfield', 'weight' => 0]);
        $formDisplay->setComponent('field_body', ['type' => 'text_textarea', 'weight' => 1]);
        $formDisplay->setComponent('field_link', ['type' => 'link_default', 'weight' => 2]);
        $formDisplay->save();
    }
}
