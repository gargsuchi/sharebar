<?php

/* core/modules/system/templates/form-element-label.html.twig */
class __TwigTemplate_6c03a9651fb02c8d6f5d96ba7ad62e73090da401fae24752d5781b6540ba2d50 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 16
        if (((!twig_test_empty((isset($context["title"]) ? $context["title"] : null))) || (isset($context["required"]) ? $context["required"] : null))) {
            // line 17
            echo "<label";
            echo twig_drupal_escape_filter($this->env, (isset($context["attributes"]) ? $context["attributes"] : null), "html", null, true);
            echo ">";
            echo twig_drupal_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
            echo "</label>";
        }
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/form-element-label.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  21 => 17,  91 => 68,  85 => 65,  80 => 64,  77 => 63,  71 => 61,  68 => 60,  62 => 58,  60 => 57,  55 => 56,  49 => 53,  44 => 52,  41 => 51,  35 => 49,  32 => 48,  26 => 46,  24 => 45,  19 => 16,);
    }
}
