<?php

namespace WP_TMT\Core\Concerns;

/**
 * Shares a way to render PHP templates passing variables from a View_Model if it's present.
 */
trait RenderableTemplate
{

  /**
   * Render a PHP template.
   *
   */
  public function render()
  {
    if ($this->shouldRenderTemplate()) {

      if ($this->hasViewModelArgs())
        extract($this->viewModel);

      include $this->templatePath;
    }
  }

  protected function shouldRenderTemplate()
  {
    return isset($this->templatePath) && is_readable($this->templatePath);
  }

  protected function hasViewModelArgs()
  {
    return
      isset($this->viewModel)
      && is_array($this->viewModel)
      && !empty($this->viewModel);
  }
}
