<?php
/**
 * Class helpers
 * @package App\Helpers
 * @author Kashif <kash@dreamsites.co.uk>
 * @created 28/09/2020
 */

use Illuminate\Support\Str;

/**
 * @param int $index
 * @return string
 */
function generateString(int $index): string
{
    $action = app('request')->route()->getAction();
    $controller = class_basename($action['controller']);

    $uri = Route::current();

    foreach ($uri as $i) {
        $url = $i;
        break;
    }

    $pageType = Str::ucfirst(explode('Controller@', $controller)[1]);
    if (stripos($pageType, 'index') !== false) {
        $pageType = __('List ');
        $pageName = explode('Controller@', $controller)[0];
    } else {
        $pageName = Str::singular(explode('Controller@', $controller)[0]);
    }

    if ($index === 4) {
        return $url;
    } else if ($index === 1) {
        return __('Create') . ' ' . Str::singular($pageName);
    } else if ($index === 2) {
        return route(Str::plural(strtolower($pageName)) . '.create');
    } else if ($index === 3) {
        return Str::plural(strtolower($pageName));
    }

    return $pageType . ' ' . $pageName;
}

