<?php
declare(strict_types=1);

namespace Denkwerk\MosparoForm\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;
use TYPO3\CMS\Form\Event\BeforeRenderableIsValidatedEvent;

final class BeforeRenderableIsValidatedEventListener
{
    /**
     * This listener will add the current FormDefinition to the "TYPO3_REQUEST", because we need the form definition in
     * the MosparoCaptchaValidator to verify the validated fields.
     *
     * @param BeforeRenderableIsValidatedEvent $event
     * @return void
     */
    #[AsEventListener('mosparo-form/before-renderable-is-validated')]
    public function __invoke(BeforeRenderableIsValidatedEvent $event): void
    {
        // We only want to add the definition on the first call of the method when $renderable should be of type "Page"
        if ($event->renderable instanceof Page &&
            isset($GLOBALS['TYPO3_REQUEST']) &&
            $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequest &&
            $GLOBALS['TYPO3_REQUEST']->getAttribute('mosparoFormDefinition') === null
        ) {
            $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST']->withAttribute(
                'mosparoFormDefinition',
                $event->formRuntime->getFormDefinition()
            );
        }
    }
}
