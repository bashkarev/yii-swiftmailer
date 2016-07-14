<?php

namespace bashkarev\swiftmailer;

/**
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 */
class View
{

    /**
     * @param $view
     * @param array $params
     * @param null $context
     * @return string
     */
    public function render($view, $params = [], $context = null)
    {
        $viewFile = $context->getViewPath() . '/' . $view . '.php';
        return $this->renderPhpFile($viewFile, $params, $context);
    }

    /**
     * Renders a view file as a PHP script.
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     */
    public function renderPhpFile($_file_, $_params_ = [])
    {
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        require($_file_);
        return ob_get_clean();
    }

}