<?php

namespace IIIFStorage\Media;

use Digirati\OmekaShared\Utility\PropertyIdSaturator;
use Digirati\OmekaShared\Framework\AbstractIngester;
use IIIFStorage\Utility\CheapOmekaRelationshipRequest;
use Omeka\Api\Manager;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Media\Ingester\IngesterInterface;
use Zend\Form\Element;
use Zend\View\Renderer\PhpRenderer;

class CanvasSnippetIngester extends AbstractIngester implements IngesterInterface
{
    private $api;
    /**
     * @var PropertyIdSaturator
     */
    private $saturator;
    /**
     * @var CheapOmekaRelationshipRequest
     */
    private $relationshipRequest;

    public function __construct(
        Manager $api,
        PropertyIdSaturator $saturator,
        CheapOmekaRelationshipRequest $relationshipRequest
    ) {
        $this->api = $api;
        $this->saturator = $saturator;
        $this->relationshipRequest = $relationshipRequest;
    }

    /**
     * Get a human-readable label for this ingester.
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Canvas snippet';
    }

    /**
     * Get the name of the renderer for media ingested by this ingester
     *
     * @return string
     */
    public function getRenderer()
    {
        return 'iiif-canvas-snippet';
    }

    public function renderFormElements(PhpRenderer $view, array $formElements)
    {
        $elements = parent::renderFormElements($view, $formElements); // TODO: Change the autogenerated stub
        return implode('', [
            $elements,
            '<script>$("body").on(\'click [data-media-type="iiif-canvas-snippet"]\', function () { $("#canvas-select").chosen(chosenOptions); });</script>',
        ]);
    }

    /**
     * @param string $operation either "create" or "update"
     * @return Element[]
     */
    public function getFormElements(string $operation): array
    {
        $canvas = new Element\Text('canvas');
        $canvas->setAttributes([
            'required' => false,
        ]);
        $canvas->setOptions([
            'label' => 'Canvas ID', // @translate
            'info' => 'Paste in an ID of the canvas to be displayed.', // @translate
        ]);

        return [
            $canvas
        ];
    }

    public function getManifestLabel(ItemRepresentation $item): string
    {
        $manifest = $this->relationshipRequest->getManifestFromCanvas($item->id());
        return $manifest['label'] ?? '';
    }
}
