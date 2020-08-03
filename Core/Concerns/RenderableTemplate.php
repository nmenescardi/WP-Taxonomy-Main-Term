<?php

namespace WP_TMT\Core\Concerns;

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
