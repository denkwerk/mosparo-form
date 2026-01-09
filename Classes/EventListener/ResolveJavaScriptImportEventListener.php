<?php
declare(strict_types=1);

namespace Denkwerk\MosparoForm\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Page\Event\ResolveJavaScriptImportEvent;

final class ResolveJavaScriptImportEventListener
{
    #[AsEventListener]
    public function __invoke(ResolveJavaScriptImportEvent $event): void
    {
        if ($event->specifier === '@typo3/backend/settings/editor.js') {
            $event->importMap->includeImportsFor('@denkwerk/mosparo-form/settings/type/password.js');
        }
    }
}

